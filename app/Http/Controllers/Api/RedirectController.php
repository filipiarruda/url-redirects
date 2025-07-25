<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Redirect;
use App\Models\RedirectLog;
use Hashids\Hashids;
use Illuminate\Support\Facades\Validator;

class RedirectController extends Controller
{
    protected $hashids;

    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => ['required', 'regex:/^https:\/\/.+$/'],
        ], [
            'url.regex' => 'Só é permitido cadastrar redirecionamento a URLs HTTPS. Verifique sua URL e tente novamente.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $validateUrlStatusCode = $this->validateUrlStatus($request->url);

        if ($validateUrlStatusCode !== 200) {
            return response()->json([
                'message' => 'A URL fornecida não está acessível ou não é válida.'
            ], 422);
        }

        $lastId = Redirect::max('id') ?? 0;
        $nextId = $lastId + 1;
        $code = $this->hashids->encode($nextId);

        $redirect = Redirect::create([
            'redirect_url' => $request->url,
            'code' => $code,
            'active' => true,
        ]);

        return response()->json(['message' => 'Link de redirecionamento criado com sucesso!', 'redirect' => $redirect], 201);
    }


    public function index()
    {
        $redirects = Redirect::all()->map(function ($item) {
            $item->short_url = url('/r/' . $item->code);
            return $item;
        });

        return response()->json(['redirects' => $redirects]);
    }


    public function stats($code)
    {
        $redirect = Redirect::where('code', $code)->firstOrFail();
        $topReferers = RedirectLog::topReferrers($redirect->id);
        $lastDaysAccess = RedirectLog::lastDaysAccess($redirect->id);

        $stats = [
            'total_accesses' => $redirect->logs()->count(),
            'unique_ips' => $redirect->logs()->distinct('request_ip')->count('request_ip'),
            'top_referers' => $topReferers->map(function ($item) {
                return [
                    'referer' => $item->request_referer,
                    'total' => $item->total,
                ];
            }),
            'last_10_days_access' => $lastDaysAccess,
        ];

        $redirect->stats = $stats;
        return response()->json(['redirect' => $redirect]);
    }

    private function validateUrlStatus ($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode;
    }
}
