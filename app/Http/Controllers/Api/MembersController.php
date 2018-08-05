<?php

namespace App\Http\Controllers\Api;

use App\Http\Validators\MembersValidator;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\ResponseCache\Facades\ResponseCache;

class MembersController extends Controller
{
    protected $client;

    public function __construct(Request $request)
    {
        $user = Auth::user();
        $apiKey = $user->mailchimp_api_key;

        $this->client = new Client(['headers' => ['Authorization' => 'apikey ' . $apiKey]]);
    }

    /**
     * Display a listing of the members.
     * @param $listId
     * @return JsonResponse
     */
    public function index($listId)
    {
        try {
            $response = $this->client->request('GET', env('MAILCHIMP_API_URL'). '/lists/' . $listId . '/members', []);
        } catch (GuzzleException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    /**
     * Store a newly created member in list.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $listId
     * @return JsonResponse
     */
    public function store(Request $request, $listId)
    {
        $validator = MembersValidator::store($request);

        if (!is_bool($validator)) {
            return $validator;
        }

        try {
            $body = $request->all();

            if ($request->get('vip')) {
                $body['vip'] = ($request->input('vip') === 'false') ? false : true;
            }

            $response = $this->client->request('POST', env('MAILCHIMP_API_URL'). '/lists/' . $listId . '/members',
                [\GuzzleHttp\RequestOptions::JSON => $body]);
        } catch (GuzzleException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        ResponseCache::flush();
        return $response;

    }

    /**
     * Display the specified member.
     *
     * @param $listId
     * @param $subscriberHash
     * @return JsonResponse
     */
    public function show($listId, $subscriberHash)
    {
        try {
            $response = $this->client->request(
                'GET', env('MAILCHIMP_API_URL'). 'lists/' . $listId . '/members/' . $subscriberHash, []);
        } catch (GuzzleException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    /**
     * Update the specified member in list.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $listId
     * @param $subscriberHash
     * @return JsonResponse
     */
    public function update(Request $request, $listId, $subscriberHash)
    {
        $validator = MembersValidator::store($request);

        if (!is_bool($validator)) {
            return $validator;
        }

        try {
            $body = $request->all();

            if ($request->get('vip')) {
                $body['vip'] = ($request->input('vip') === 'false') ? false : true;
            }

            $response = $this->client->request(
                'PATCH', env('MAILCHIMP_API_URL'). 'lists/' . $listId . '/members/' . $subscriberHash,
                [\GuzzleHttp\RequestOptions::JSON => $body]);
        } catch (GuzzleException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        ResponseCache::flush();
        return $response;
    }

    /**
     * Remove the specified member from list.
     *
     * @param $listId
     * @param $subscriberHash
     * @return JsonResponse
     */
    public function destroy($listId, $subscriberHash)
    {
        try {
            $this->client->request(
                'DELETE', env('MAILCHIMP_API_URL'). '/lists/' . $listId . '/members/' . $subscriberHash, []);
        } catch (GuzzleException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        ResponseCache::flush();
        return new JsonResponse('The member has been deleted successfully.', 200);
    }
}
