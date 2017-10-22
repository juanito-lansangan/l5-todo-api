<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Helpers\LogFormatter;

class BaseController extends Controller
{

    /**
     * Metadata
     * @var array
     */
    protected $meta;

    /**
     * Header
     * @var array
     */
    protected $headers;

    public function sendResponse($data, $status = Response::HTTP_OK)
    {
        $response = [
            'meta' => $this->createMeta($status),
            'data' => $data,
        ];
        LogFormatter::info($response);
        return response($response, $status, $this->getHeaders());
    }

    private function createMeta($statusCode)
    {
        $request = request();
        $meta = $this->meta;
        $meta['status'] = $statusCode;
        $meta['self'] = url($request->path());

        return $meta;
    }

    /**
     * Return header data
     * @return array
     */
    private function getHeaders()
    {
        return $this->headers ?? [];
    }

    /**
     * Set header data
     *
     * @param array $headers
     */
    public function setHeaders($headers = [])
    {
        $this->headers = $headers;
    }

    public function sendError($message, $status, $code = 'NAN', $errors = null, $trace = null)
    {
        // single or multiple messages
        $msg = is_array($message) ? 'messages' : 'message';

        // set meta data for error
        $meta = [
            $msg => $message,
            'code' => $code,
            'errors' => $errors,
        ];

        // debug trace if debug is active
        if ($trace) {
            $meta['trace'] = $trace;
        }

        // set meta for response
        $this->setMeta($meta);

        // response data
        $response = [
            'meta' => $this->createMeta($status),
            'messages' => $message,
            'data' => $errors
        ];
        return response($response, $status, $this->getHeaders());
    }

    public function sendErrorLog($errors)
    {
        LogFormatter::error($errors);
    }

    /**
     * Set metadata
     *
     * @param array $meta
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }

    /**
     * @param $message
     * @param $statusCode
     *
     * @throws Exception
     */
    public function error($message, $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $this->response->error($message, $statusCode);
    }

    /**
     * @param $message
     *
     * @throws Exception
     */
    public function errorNotFound($message)
    {
        $this->response->errorNotFound($message);
    }

    /**
     * @param $message
     *
     * @throws Exception
     */
    public function errorBadRequest($message)
    {
        $this->response->errorBadRequest($message);
    }

    /**
     * @param $message
     *
     * @throws Exception
     */
    public function errorForbidden($message)
    {
        $this->response->errorForbidden($message);
    }

    /**
     * @param $message
     *
     * @throws Exception
     */
    public function errorInternal($message)
    {
        $this->response->errorInternal($message);
    }

    /**
     * @param $message
     *
     * @throws Exception
     */
    public function errorUnauthorized($message)
    {
        $this->response->errorUnauthorized($message);
    }
}
