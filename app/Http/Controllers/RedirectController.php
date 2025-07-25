<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Redirect;
use App\Models\RedirectLog;

class RedirectController extends Controller
{
    public function redirectUrl($code, Request $request)
    {

        $redirect = Redirect::where('code', $code)->firstOrFail();
        $queryStringSafe = $request->getQueryString() ?? '';
        $data = [
            'redirect_id' => $redirect->id,
            'request_ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'request_referer' => $request->header('Referer'),
            'request_query_params' => $queryStringSafe,
        ];

        $redirectLog = RedirectLog::create($data);

        $parsedRedirectUrl = $this->parseQueryParams($queryStringSafe, $redirect->redirect_url);

        return view('redirect-delay', [
            'url' => $parsedRedirectUrl ?? $redirect->redirect_url,
            'active' => $redirect->active ?? true,
        ]);
    }

    private function parseQueryParams($requestParams, $redirectUrl) {

        if (strpos($redirectUrl, '?') !== false) {
            $redirectUrlQuery = $redirectUrl . '&' . $requestParams;
        } else {
            $redirectUrlQuery = $redirectUrl . '?' . $requestParams;
        }
        return $redirectUrlQuery;
    }
}
