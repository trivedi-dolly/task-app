<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

  /**
 * @OA\Info(
 *    title="Test App",
 *    version="1.0.0",
 * )
 */

class ApiController extends Controller
{
 

 /**
 * @OA\Get(
 *     path="/api/list",
 *     @OA\Response(response="200", description="An example endpoint")
 * )
 */

    function list()
    {
        $user = User::get();
        return response()->json([
            'status' => 200,
            'data' => $user
        ]);
    }

     /**
        * @OA\Post(
        * path="/api/add",
        * operationId="add-Users",
        * tags={"Users"},
        * summary="Users",
        * description="Users here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"image", "name", "email","phone", "education_id", "gender", "hobbies", "experience", "message"},
        *               @OA\Property(property="image", type="file"),
        *               @OA\Property(property="name", type="string"),
        *               @OA\Property(property="email", type="string"),
        *               @OA\Property(property="phone", type="integer"),
        *               @OA\Property(property="education_id", type="integer"),
        *               @OA\Property(property="gender", type="string"),
        *               @OA\Property(property="hobbies", type="string"),
        *               @OA\Property(property="experience", type="longtext"),
        *               @OA\Property(property="message", type="longtext"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="comment has been created successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="comment has been created successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    function add(Request $request)
    {
        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');

                $imagePath = 'storage/' . $imagePath;
            } else {
                $imagePath = null;
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'hobbies' => $request->hobbies,
                'education' => $request->education,
                'experience' => is_array($request->experience) ? implode($request->experience) : $request->experience,
                'image' => $imagePath,
                'gender' => $request->gender
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'User created successfully',
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
    function update($id, Request $request)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $data = $request->only(['name', 'email', 'phone', 'hobbies', 'education', 'experience', 'image', 'gender']);
                $user->fill($data)->update();
                return response()->json([
                    'status' => 200,
                    'message' => 'User updated successfully'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
    /**
     * @OA\Delete(
     *    path="/api/{id}",
     *    operationId="delete users",
     *    tags={"Users"},
     *    summary="Delete Users",
     *    security={{"bearer_token":{}}} ,
     *    description="Delete Schedule",
     *    @OA\Parameter(name="id", in="path", description="Id of users", required=true,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="status_code", type="integer", example="200"),
     *         @OA\Property(property="data",type="object")
     *          ),
     *       )
     *      )
     *  )
     */
    function delete($id)
    {
        try {
            User::where('id', $id)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
}