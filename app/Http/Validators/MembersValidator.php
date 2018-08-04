<?php

namespace App\Http\Validators;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class MembersValidator
{
    public static function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_address' => 'required|string',
            'email_type' => 'string',
            'status' => 'required|string|in:subscribed,unsubscribed,cleaned,pending',
            'merge_fields' => 'array',
            'interests' => 'array',
            'language' => 'string',
            'vip' => 'boolean',
            'location' => 'array',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), 402);
        }

        if ($request->input('location')) {
            $location = $request->input('location');

            $validator = Validator::make($location, [
                'latitude' => 'integer',
                'longitude' => 'integer',
            ]);
        }

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), 402);
        }

        return true;
    }

    public static function update()
    {
        $validator = Validator::make($request->all(), [
            'email_address' => 'string',
            'email_type' => 'string',
            'status' => 'string',
            'merge_fields' => 'array',
            'interests' => 'array',
            'language' => 'string',
            'vip' => 'boolean',
            'location' => 'array',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), 402);
        }

        if ($request->input('location')) {
            $location = $request->input('location');

            $validator = Validator::make($location, [
                'latitude' => 'integer',
                'longitude' => 'integer',
            ]);
        }

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), 402);
        }

        return true;
    }
}