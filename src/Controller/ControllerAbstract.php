<?php
namespace App\Controller;


use App\Interfaces\InterfaceController;
use Psr\Http\Message\ServerRequestInterface;


class ControllerAbstract implements InterfaceController
{
    protected function getRequestValue(ServerRequestInterface $request, $key)
    {

        if($getParam = $request->getQueryParams()) {
            if(!empty($getParam[$key])) {
                return $getParam[$key];
            }
        }

        if($postParam = json_decode($request->getBody()->getContents(), true)) {
            if(!empty($postParam[$key])) {
                return $postParam[$key];
            }
        }

        if($postParam = $request->getParsedBody()) {
            if(!empty($postParam[$key])) {
                return $postParam[$key];
            }
        }

        return false;
    }

}