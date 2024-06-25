<?php

namespace App\Http\Controllers\Api;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class DoctorAuthController extends Controller
{
    /**
     * Register a new Doctor.
     * This endpoint lets you register  a new user
     *@subgroup doctor
     * @bodyParam name string required The name of the user.
     * @bodyParam email string required valid email address The email of the user.
     * @bodyParam password string required min:8 The password of the user.
     * 
     * @response {
     *     "doctor": {
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
    public function doctorRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $doctor = Doctor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Auth::guard('doctor')->login($doctor);
        // Return a JSON response indicating success
        return response()->json(['Doctor' => $doctor,
        'message' => 'Doctor registered successfully', 
        "token" => $doctor->createToken('appToken')->accessToken], 201);
    }

   /**
     * Log in a doctor.
     * This endpoint lets you login as authenticated user
     *@subgroup doctor
     * @bodyParam email string required valid email address The email of the user.
     * @bodyParam password string required min:8 The password of the user.
     * 
     * @response {
     *     "success": true,
     *     "token": "eyJhbGciOiAiSFMyNTYiLCJraWQiOiAiYWMwMDZmMDYtMGM0ZC00MTQ5LWE5MTYtYjEwYzQ2N2YzZmMwIn0.eyJqdGkiOiAibG9naW4tY29",
     *     "doctor": {
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
    public function doctorLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8']
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::guard('doctor')->attempt($credentials)) {
            $doctor = Auth::guard('doctor')->user();
            return response()->json([
                'status' => true,
                'token' => $doctor->createToken('appToken')->accessToken,
                'doctor' => $doctor,
                'message' => 'Doctor logged in successfully',
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }
 /**
     * Retrieve the authenticated doctor's profile.
     *
     * @group doctor Management
     * 
     * @response {
     *     "status": true,
     *     "data": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "degree": "mbbs",
     *         "treatment": "legbreak",
     *         "patient_name": "buuzo",
     *         "created_at": "2024-06-24T12:34:56Z",
     *         "updated_at": "2024-06-24T12:34:56Z"
     *     },
     *     "message": "get doctor profile successfully"
     * }
     * 
     * @response 401 {
     *     "message": "Unauthenticated."
     * }
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorProfile(Request $request){
        // dd($request);
        $doctor = Auth::guard('doctor_api')->user();
        // dd($user);
        return response()->json([
                'status' => true,
                'data' => $doctor,
                'message' => 'get doctor profile successfully',
            ]);
    }
   
     /**
     * Log out the authenticated doctor.
     *
     * @group Authentication
     * 
     * @response {
     *     "success": true,
     *     "message": "Logged out successfully.",
     *     "doctor": {
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
     * @return \Illuminate\Http\JsonResponse
     */

    public function doctorProfileLogout(){
        try {
            if (Auth::guard('doctor_api')->check()) {
                $doctor = Auth::guard('doctor_api')->user();
                $doctor->token()->revoke();
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully.',
                    'user' => $doctor,
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
     * Update the authenticated doctor's profile.
     *
     * @group doctor Management
     * @bodyParam name string required The name of the user.
     * @bodyParam email string required valid email address The email of the doctor. Must be unique (ignoring current doctor's ID).
     * @bodyParam treatment string required The treatment name for the pet.
     * @bodyParam degree string required The degree of the doctor.
     * @bodyParam patient_name string required The patient_name(pet name).
     * 
     * @response {
     *     "status": true,
     *     "message": "doctor updated successfully",
     *     "data": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "petname": "Buddy",
     *         "breed": "Labrador Retriever",
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
    public function doctorProfileUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
            'patient_name' => ['required', 'string', 'max:255'],
            'degree' => ['required', 'string', 'max:255'],
            'treatment' => ['required', 'string', 'max:255'],
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {
            $doctor = Auth::guard('doctor_api')->user();
            if (!$doctor) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            // dd($doctor);
            // $doctor = Doctor::findOrFail($request->id);
            $doctor->name = $request->name;
            $doctor->email = $request->email;
            $doctor->patient_name = $request->patient_name;
            $doctor->degree = $request->degree;
            $doctor->treatment = $request->treatment;
            $doctor->save();
            return response()->json([
                'status' => true,
                'message' => "doctor updated successfully",
                'data' => $doctor,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }

    }

     /**
     * Createe the authenticated doctor's profile.
     *
     * @group doctor Management
     * @bodyParam name string required The name of the doctor.
     * @bodyParam email string required valid email address The email of the doctor. Must be unique (ignoring current doctor's ID).
     * @bodyParam treatment string required The treatment name for the pet.
     * @bodyParam degree string required The degree of the doctor.
     * @bodyParam patient_name string required The patient_name(pet name).
     * 
     * @response {
     *     "status": true,
     *     "message": "doctor updated successfully",
     *     "data": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "petname": "Buddy",
     *         "breed": "Labrador Retriever",
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
    public function doctorProfileCreate(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
            'patient_name' => ['required', 'string', 'max:255'],
            'degree' => ['required', 'string', 'max:255'],
            'treatment' => ['required', 'string', 'max:255'],
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {
            $doctor = Auth::guard('doctor_api')->user();
            if (!$doctor) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            $doctor= new Doctor;
            $doctor->name = $request->name;
            $doctor->email = $request->email;
            $doctor->patient_name = $request->patient_name;
            $doctor->degree = $request->degree;
            $doctor->treatment = $request->treatment;
            $doctor->save();
            return response()->json([
                'status' => true,
                'message' => "doctor created successfully",
                'data' => $doctor,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
    
    /**
     * Delete the authenticated doctor's profile.
     *
     * @group doctor Management
     * @urlParam id required The ID of the doctor to delete.
     * 
     * @response {
     *     "status": true,
     *     "message": "doctor deleted successfully",
     *     "data": {
     *         "name": "John Doe",
     *         "email": "johndoe@example.com",
     *         "petname": "Buddy",
     *         "breed": "Labrador Retriever",
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
    public function doctorprofiledelete(Request $request){
        // dd($request);
        try {
            $doctor = Auth::guard('doctor_api')->user();
            // dd($doctor);
            if (!$doctor) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            // $doctor = Doctor::find($request->id);
            $doctor->delete();
            return response()->json([
                'status' => true,
                'message' => "doctor deleted successfully",
                'data' => $doctor,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
