<?php


namespace App\Helpers;

use Illuminate\Http\JsonResponse;

/**
 * Class APIHelper
 * @package App\Helpers
 */
class APIHelper
{
    /**
     * @param bool $status
     * @param string $message
     * @param null $data
     * @param int $status_code
     * @return JsonResponse
     */
    public static function makeAPIResponse($status = true, $message = "Done", $data = null, $status_code = 200)
    {
        $response = [
            "success" => $status,
            "message" => $message,
        ];
        if ($data != null || is_array($data)) {
            $response["data"] = $data;
        }
        return response()->json($response, $status_code);
    }


}
