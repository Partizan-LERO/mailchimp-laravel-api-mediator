<?php

namespace App\Http\Controllers\Api;

use App\Http\Validators\ListsValidator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\ResponseCache\Facades\ResponseCache;


class ListsController extends Controller
{
    protected $client;

    public function __construct()
    {
        $user = Auth::user();
        $apiKey = $user->mailchimp_api_key;

        $this->client = new Client(['headers' => ['Authorization' => 'apikey ' . $apiKey]]);
    }

    /**
     * Display lists.
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $response = $this->client->request('GET', env('MAILCHIMP_API_URL'). 'lists', []);

        } catch (GuzzleException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    /**
     * Store a newly created list.
     *
     * @param Request $request
     * @return JsonResponse|mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function store(Request $request)
    {
        $validator = ListsValidator::store($request);

        if (!is_bool($validator)) {
          return $validator;
        }

        try {
            $body = $request->all();
            $body['email_type_option'] = ($request->input('email_type_option') === 'false') ? false : true;

            if ($request->get('use_archive_bar')) {
                $body['use_archive_bar'] = ($request->input('use_archive_bar') === 'false') ? false : true;
            }

            $response = $this->client->request('POST', env('MAILCHIMP_API_URL'). 'lists',
                [\GuzzleHttp\RequestOptions::JSON => $body]);

        } catch (GuzzleException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        ResponseCache::flush();
        return $response;

    }

    /**
     * Display the specified list.
     * @param $listId
     * @return JsonResponse|mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function show($listId)
    {
        try {
            $response = $this->client->request('GET', env('MAILCHIMP_API_URL'). 'lists/' . $listId, []);
        } catch (GuzzleException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    /**
     * Update the specified list.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $listId
     * @return JsonResponse|\Illuminate\Http\Response|string
     */
    public function update(Request $request, $listId)
    {
        $validator = ListsValidator::store($request);

        if (!is_bool($validator)) {
            return $validator;
        }

        try {
            $body = $request->all();
            $body['email_type_option'] = ($request->input('email_type_option') === 'false') ? false : true;

            if ($request->get('use_archive_bar')) {
                $body['use_archive_bar'] = ($request->input('use_archive_bar') === 'false') ? false : true;
            }

            $response = $this->client->request('PATCH', env('MAILCHIMP_API_URL'). 'lists/' . $listId, [\GuzzleHttp\RequestOptions::JSON => $body]);
        } catch (GuzzleException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        ResponseCache::flush();
        return $response;
    }

    /**
     * Remove the specified list.
     *
     * @param $listId
     * @return JsonResponse
     */
    public function destroy($listId)
    {
        try {
            $this->client->request('DELETE', env('MAILCHIMP_API_URL'). '/lists/' . $listId, []);
        } catch (GuzzleException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        ResponseCache::flush();
        return new JsonResponse('The list has been deleted successfully.', '200');
    }
}
