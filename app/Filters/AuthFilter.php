<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    protected $userData;
    public $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userData = null;
        if (!is_null(session()->userid)) {
            $uM = model('UsersModel');
            if ($this->userData = $uM->getBy('id', session()->userid)) {
                //
            } else {
                $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Ooops! El usuario no está disponible.", "title": "¡Ooops!"}');
                return redirect()->to(base_url('auth/login'));
                // return redirect()->to(base_url('auth/login'))->with('mensaje', [
                //     'type' => 'danger',
                //     'txt' => 'Ooops! El usuario no está disponible.'
                // ]);
            }
        }
    }


    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Redirigimos al user al login si no está logueado y quiere acceder a una sección que necesita autenticación
        if (is_null(session()->get('logueado')) || session()->get('logueado') === false) {
            $this->session->setFlashdata('toastr', '{"tipo": "error", "msg": "Ooops! Necesitas loguearte para acceder al recurso solicitado.", "title": "¡Ooops!"}');
            return redirect()->to(base_url('auth/login'));
            // return redirect()->to(base_url('auth/login'))->with('mensaje', [
            //     'type' => 'warning',
            //     'txt' => 'Ooops! Necesitas loguearte para acceder al recurso solicitado.'
            // ]);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Lanzamos excepción 403 si el user no pertenece a un grupo con privilegio de ver el recurso actual
        if (is_array($arguments) && count($arguments) > 0) {
            if (!in_array($this->userData['name_group'], $arguments)) {
                $vista = view('errors/html/error_403');
                $response->setStatusCode(403)->setBody($vista);
                throw new \CodeIgniter\Router\Exceptions\RedirectException('', 403);
            }
        }
    }
}
