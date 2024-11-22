<?php

namespace App\Http\Libraries;

class ResponseCode
{
    public function OKE($msg = null, $datas = null)
    {
        return response()->json(["status" => 200, "messages" => $msg, "datas" => $datas], 200);
    }
    public function CREATED($msg = null, $datas = null)
    {
        return response()->json(["status" => 201, "messages" => $msg, "datas" => $datas], 201);
    }
    public function ACCEPTED($msg = null, $datas = null)
    {
        return response()->json(["status" => 202, "messages" => $msg, "datas" => $datas], 202);
    }
    public function BAD_REQUEST($msg = null, $datas = null)
    {
        return response()->json(["status" => 400, "messages" => $msg, "datas" => $datas], 400);
    }
    public function UNAUTHORIZED($msg = null, $datas = null)
    {
        return response()->json(["status" => 401, "messages" => $msg, "datas" => $datas], 401);
    }
    public function FORBIDDEN($msg = null, $datas = null)
    {
        return response()->json(["status" => 403, "messages" => $msg, "datas" => $datas], 403);
    }
    public function NOT_FOUND($msg = null, $datas = null)
    {
        return response()->json(["status" => 404, "messages" => $msg, "datas" => $datas], 404);
    }
    public function UNPROCESS_ENTITY($msg = null, $datas = null)
    {
        return response()->json(["status" => 422, "messages" => $msg, "datas" => $datas], 422);
    }
    public function TO_MANY_REQ($msg = null, $datas = null)
    {
        return response()->json(["status" => 429, "messages" => $msg, "datas" => $datas], 429);
    }
    public function SERVER_ERROR($msg = null, $datas = null)
    {
        return response()->json(["status" => 500, "messages" => $msg, "datas" => $datas], 500);
    }
    public function SERVER_TIMEOUT($msg = null, $datas = null)
    {
        return response()->json(["status" => 504, "messages" => $msg, "datas" => $datas], 504);
    }
}
