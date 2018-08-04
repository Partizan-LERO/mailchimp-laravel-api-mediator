<?php

namespace App\Http\Validators;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListsValidator
{
    public static function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact' => 'required|array',
            'permission_reminder' => 'required|string',
            'use_archive_bar' => 'boolean',
            'campaign_defaults' => 'required|array',
            'email_type_option' => 'required|string',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), '400');
        }

        $contact = $request->input('contact');

        $validator = Validator::make($contact, [
            'company' => 'required|string',
            'address1' => 'required|string',
            'address2' => 'string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'country' => 'required|string',
            'phone' => 'string',
            'notify_on_subscribe' => 'string',
            'notify_on_unsubscribe' => 'string',
            'visibility' => 'string',
            'double_optin' => 'boolean',
            'marketing_permissions' => 'boolean',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), '400');
        }

        $campaignDefaults = $request->input('campaign_defaults');

        $validator = Validator::make($campaignDefaults, [
            'from_name' => 'required|string',
            'from_email' => 'required|email',
            'subject' => 'required|string',
            'language' => 'required|string',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), '400');
        }

        return true;
    }

    public static function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact' => 'required|array',
            'permission_reminder' => 'required|string',
            'use_archive_bar' => 'boolean',
            'campaign_defaults' => 'required|array',
            'email_type_option' => 'required|string',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), '400');
        }

        $contact = $request->input('contact');

        $validator = Validator::make($contact, [
            'company' => 'required|string',
            'address1' => 'required|string',
            'address2' => 'string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip' => 'required|string',
            'country' => 'required|string',
            'phone' => 'string',
            'notify_on_subscribe' => 'string',
            'notify_on_unsubscribe' => 'string',
            'visibility' => 'string',
            'double_optin' => 'boolean',
            'marketing_permissions' => 'boolean',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), '400');
        }

        $campaignDefaults = $request->input('campaign_defaults');

        $validator = Validator::make($campaignDefaults, [
            'from_name' => 'required|string',
            'from_email' => 'required|email',
            'subject' => 'required|string',
            'language' => 'required|string',
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), '400');
        }

        return true;
    }
}