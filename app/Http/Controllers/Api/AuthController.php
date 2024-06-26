<?php

/**
 * @group User
 * 
 * API'S to manage the user profile
 */

namespace App\Http\Controllers\Api;

use Intervention\Image\Facades\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
// use Illuminate\Contracts\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *@subgroup user
     * @bodyParam name string required The name of the user.
     * @bodyParam email string required valid email address The email of the user.
     * @bodyParam password string required min:8 The password of the user.
     * 
     * @response 200 {
     *     "user": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "created_at": "2024-06-24T12:34:56Z",
     *         "updated_at": "2024-06-24T12:34:56Z"
     *     },
     *     "token": "eyJhbGciOiAiSFMyNTYiLCJraWQiOiAiYWMwMDZmMDYtMGM0ZC00MTQ5LWE5MTYtYjEwYzQ2N2YzZmMwIn0.eyJqdGkiOiAibG9naW4tY29"
     * }
     * 
     * @response 422 {
     *     "errors": {
     *         "email": [
     *             "The email has already been taken."
     *         ]
     *     }
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'user' => $user,
            "token" => $user->createToken('appToken')->accessToken
        ], 201);
    }

    /**
     * Log in a user.
     *@subgroup user
     * @bodyParam email string required valid email address The email of the user.
     * @bodyParam password string required min:8 The password of the user.
     * 
     * @response {
     *     "success": true,
     *     "token": "eyJhbGciOiAiSFMyNTYiLCJraWQiOiAiYWMwMDZmMDYtMGM0ZC00MTQ5LWE5MTYtYjEwYzQ2N2YzZmMwIn0.eyJqdGkiOiAibG9naW4tY29",
     *     "user": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "created_at": "2024-06-24T12:34:56Z",
     *         "updated_at": "2024-06-24T12:34:56Z"
     *     }
     * }
     * 
     * @response 401 {
     *     "success": false,
     *     "message": "Failed to authenticate."
     * }
     * 
     * @response 500 {
     *     "success": false,
     *     "message": "Internal Server Error"
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // successfull authentication
                /** @var \App\Models\User $user **/
                $user = Auth::user();

                return response()->json([
                    'success' => true,
                    'token' => $user->createToken('appToken')->accessToken,
                    'user' => $user,
                ], 200);
            } else {
                // failure to authenticate
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to authenticate.',
                ], 401);
            }
        } catch (\Exception $e) {
            Log::error("#### AuthController->login #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Log out the authenticated user.
     *
     * @group Authentication
     * 
     * @response {
     *     "success": true,
     *     "message": "Logged out successfully.",
     *     "user": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *          "image" :  "144875860.jpg",
     *         "created_at": "2024-06-24T12:34:56Z",
     *         "updated_at": "2024-06-24T12:34:56Z"
     *     }
     * }
     * 
     * @response 401 {
     *     "success": false,
     *     "message": "Failed to authenticate."
     * }
     * 
     * @response 500 {
     *     "success": false,
     *     "message": "Internal Server Error"
     * }
     * 
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy()
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $user->token()->revoke();
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully.',
                    'user' => $user,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to authenticate.',
                ], 401);
            }
        } catch (\Exception $e) {
            Log::error("#### AuthController->destroy #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve the authenticated user's profile.
     *
     * @group User Management
     * 
     * @response {
     *     "status": true,
     *     "data": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "image" :  "144875860.jpg",
     *         "created_at": "2024-06-24T12:34:56Z",
     *         "updated_at": "2024-06-24T12:34:56Z"
     *     },
     *     "message": "get user profile successfully"
     * }
     * 
     * @response 401 {
     *     "message": "Unauthenticated."
     * }
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);
            }

            return response()->json([
                'status' => true,
                'data' => $user,
                'message' => 'get user profile successfully',
            ]);
        } catch (\Exception $e) {
            Log::error("#### AuthController->profile #### " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the authenticated user's profile.
     *
     * @group User Management
     * @bodyParam name string required The name of the user.
     * @bodyParam email string required valid email address The email of the user. Must be unique (ignoring current user's ID).
     * @bodyParam image File required the image should be jpg,jpeg,svg,png.
     * @response {
     *     "status": true,
     *     "message": "User updated successfully",
     *     "data": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *          "image": "1777675756.jpg",
     *         "created_at": "2024-06-24T12:34:56Z",
     *         "updated_at": "2024-06-24T12:34:56Z"
     *     }
     * }
     * 
     * @response 401 {
     *     "error": "Unauthenticated."
     * }
     * 
     * @response 422 {
     *     "errors": {
     *          
     *             "invalid data format."
     *         
     *     }
     * }
     * 
     * @response 500 {
     *     "error": "Something went wrong."
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:6048'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            if ($request->hasFile('image')) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/images', $imageName); // Store the image in storage/app/public/images
                    $imagePath = 'storage/images/' . $imageName;
                }
            }
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->image = $imageName;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => "User updated successfully",
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Delete the authenticated user's profile.
     *
     * @group User Management
     * @urlParam id required The ID of the user to delete.
     * 
     * @response {
     *     "status": true,
     *     "message": "User deleted successfully",
     *     "data": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "created_at": "2024-06-24T12:34:56Z",
     *         "updated_at": "2024-06-24T12:34:56Z"
     *     }
     * }
     * 
     * @response 401 {
     *     "error": "Unauthenticated."
     * }
     * 
     * @response 500 {
     *     "error": "Something went wrong."
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileDelete(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            $user = User::find($request->id);
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => "User deleted successfully",
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Created the authenticated user's profile.
     *
     * @group User Management
     * @bodyParam name string required The name of the user.
     * @bodyParam email string required valid email address The email of the user. Must be unique (ignoring current user's ID).
     * @bodyParam image File required the image should be jpg,jpeg,svg,png.
     * @response {
     *     "status": true,
     *     "message": "User Created successfully",
     *     "data": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "image':17777868.jpg,
     *         "created_at": "2024-06-24T12:34:56Z",
     *         "updated_at": "2024-06-24T12:34:56Z"
     *     }
     * }
     * 
     * @response 401 {
     *     "error": "Unauthenticated."
     * }
     * 
     * @response 422 {
     *     "errors": {
     *          
     *             "invalid data format."
     *         
     *     }
     * }
     * 
     * @response 500 {
     *     "error": "Something went wrong."
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'], 
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:6048'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            if ($request->hasFile('image')) {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/images', $imageName); // Store the image in storage/app/public/images
                    $imagePath = 'storage/images/' . $imageName;
                }
            }
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->image = $imageName;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => "User created successfully",
                'data' => $user,
            ], 201);
        } catch (\Throwable $th) {

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
    /**
     * Save an image from base64 encoded string.
     *
     * @group Base64 to Image 
     * @bodyParam image string required The base64 encoded image string.
     * 
     * @response {
     *     "status": true,
     *     "message": "converted successfully",
     *     "data": {
     *         "type": "image/jpeg",
     *         "file_size": 12345,
     *         "name": "1234567890.jpg",
     *         "thumbnail_name": "thumbnail_1234567890.jpg"
     *     }
     * }
     * 
     * @response 401 {
     *     "error": "Unauthenticated."
     * }
     * 
     * @response 422 {
     *     "errors": {
     *         "image": [
     *             "The image must be a string."
     *         ]
     *     }
     * }
     * 
     * @response 500 {
     *     "status": false,
     *     "message": "converted not successfully"
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImageBase64(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => ['string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            $path = base_path('storage/app/public/images');
            // dd($request->all());
            if ($request->image) {
                $image = $request->image;
                $image_extension = explode('/', mime_content_type($image))[1];
                $image_size         = (int)(strlen(rtrim($image, '=')) * 0.75);
                $mimeData           = getimagesize($image);
                $type               = $mimeData['mime'];
                $new_name           = rand(1111, 9999) . date('mdYHis') . uniqid() . '.' . $image_extension;
                $thumbnail_name     = 'thumbnail_' . rand(1111, 9999) . date('mdYHis') . uniqid() . '.' .  $image_extension;
                #save original
                $thumbnail = Image::make($request->image)->save($path . '/' . $new_name);
                // dd($thumbnail);
                #save thumbnail
                $thumbnail->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path . '/' . $thumbnail_name);
                $mediaArray = [
                    'type' => $type,
                    'file_size' => $image_size,
                    'name' => $new_name,
                    'thumbnail_name' => $thumbnail_name,
                ];
                // dd($mediaArray);
                return response([
                    'status' => true,
                    'message' => 'converted successfully',
                    'data' => $mediaArray

                ]);
            }
            return response([
                'status' => true,
                'message' => 'Not converted successfully',

            ]);
        } catch (\Exception $e) {
            Log::error('Error processing image: ' . $e->getMessage());
            return response([
                'status' => true,
                'message' => 'converted not successfully',
            ]);
        }
    }
}
