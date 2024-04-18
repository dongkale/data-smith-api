<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Part;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Data Smith Api Documentation",
 *     description="Data Smith  Api Documentation",
 *     @OA\Contact(
 *         name="Lee Dong Kwan",
 *         email="dklee@lennon.co.kr"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * ),
 * @OA\Server(
 *     url="/api/part",
 * ),
 */
class PartController extends Controller
{
    /**
     * @OA\Get (
     *     path="/list",
     *     summary="",
     *     tags={"- 전체 리스트 요청"},
     *     description="전체 리스트 요청, header.X-API-Key에 Api Key 필요",
     *     security={{"api-key": {}},},
     *     @OA\Response(
     *         response="200",
     *         description="결과값",
     *         @OA\JsonContent(
     *             @OA\Property(property="result_code", type="int", example="0", description="성공:0, 실패:-1"),
     *             @OA\Property(property="result_message", type="string", example="Success", description="성공:Success, 실패:에러메세지"),
     *             @OA\Property(property="result_data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="name", type="string", description="이름", example="이름 1"),
     *                      @OA\Property(property="description", type="string", description="설명", example="설명 1"),
     *                      @OA\Property(property="data_json", type="string", description="JSON 문자열", example="{}"),
     *                  ),
     *                  @OA\Items(
     *                      @OA\Property(property="name", type="string", description="이름", example="sample_2"),
     *                      @OA\Property(property="description", type="string", description="설명", example="설명 2"),
     *                      @OA\Property(property="data_json", type="string", description="JSON 문자열", example="{}"),
     *                  ),
     *            )
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $parts = Part::all();
        } catch (\Exception $e) {
            Log::error("Database Query Fail: " . $e->getMessage());
            return response()->json([
                "result_code" => -1,
                "result_message" => "Database Query Fail",
            ]);
        }

        $data = [];
        foreach ($parts as $part) {
            $data[] = [
                "name" => $part->name,
                "description" => $part->description,
                "data_json" => $part->data_json,
            ];
        }

        return response()->json([
            "result_code" => 0,
            "result_message" => "Success",
            "result_data" => $data,
        ]);
    }

    /**
     * @OA\Get (
     *     path="/list/{id}",
     *     summary="",
     *     tags={"- 지정 파츠 요청"},
     *     description="지정 파츠 요청, header.X-API-Key에 Api Key 필요",
     *     security={{"api-key": {}},},
     *     @OA\Parameter(
     *         description="지정 파츠 id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         @OA\Examples(example="integer", value=1, summary="paramter"),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="결과값",
     *          @OA\JsonContent(
     *              @OA\Property(property="result_code", type="int", example="0", description="성공:0, 실패:-1"),
     *              @OA\Property(property="result_message", type="string", example="Success", description="성공:Success, 실패: 파츠가 존재 하지 않음"),
     *              @OA\Property(property="result_data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="name", type="string", description="이름", example="이름 1"),
     *                      @OA\Property(property="description", type="string", description="설명", example="설명 1"),
     *                      @OA\Property(property="data_json", type="string", description="JSON 문자열", example="{}"),
     *                  ),
     *             )
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $part = Part::where("id", "=", $id)->first();
            if (empty($part)) {
                return response()->json([
                    "result_code" => -1,
                    "result_message" => "Part not found",
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Database Query Fail: " . $e->getMessage());
            return response()->json([
                "result_code" => -1,
                "result_message" => "Database Query Fail",
            ]);
        }

        return response()->json([
            "result_code" => 0,
            "result_message" => "Success",
            "result_data" => [
                "name" => $part->name,
                "description" => $part->description,
                "data_json" => $part->data_json,
            ],
        ]);
    }

    /**
     * @OA\Get (
     *     path="/listByName/{name}",
     *     summary="",
     *     tags={"- 지정 파츠 요청(name 지정)"},
     *     description="지정 파츠 요청, header.X-API-Key에 Api Key 필요",
     *     security={{"api-key": {}},},
     *     @OA\Parameter(
     *         description="지정 파츠 name",
     *         in="path",
     *         name="name",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="name_01", summary="paramter"),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="결과값",
     *          @OA\JsonContent(
     *              @OA\Property(property="result_code", type="int", example="0", description="성공:0, 실패:-1"),
     *              @OA\Property(property="result_message", type="string", example="Success", description="성공:Success, 실패:파츠가 존재 하지 않음"),
     *              @OA\Property(property="result_data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="name", type="string", description="이름", example="이름 1"),
     *                      @OA\Property(property="description", type="string", description="설명", example="설명 1"),
     *                      @OA\Property(property="data_json", type="string", description="JSON 문자열", example="{}"),
     *                  ),
     *             )
     *         )
     *     )
     * )
     */
    public function partByName($name)
    {
        try {
            $part = Part::where("name", "=", $name)->first();
            if (empty($part)) {
                return response()->json([
                    "result_code" => -1,
                    "result_message" => "Part not found",
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Database Query Fail: " . $e->getMessage());
            return response()->json([
                "result_code" => -1,
                "result_message" => "Database Query Fail",
            ]);
        }

        return response()->json([
            "result_code" => 0,
            "result_message" => "Success",
            "result_data" => [
                "name" => $part->name,
                "description" => $part->description,
                "data_json" => $part->data_json,
            ],
        ]);
    }

    /**
     * @OA\Post (
     *     path="/save",
     *     summary="",
     *     tags={"- 파츠 저장"},
     *     description="파츠 저장, 지정 이름, 설명을 json data로 요청하면 저장한다. header.X-API-Key에 Api Key 필요",
     *     security={{"api-key": {}},},
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="이름 1", description="이름 1"),
     *              @OA\Property(property="description", type="string", example="설명 1", description="설명 1"),
     *              @OA\Property(property="data_json", type="string", example="{}", description="{}")
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="결과값",
     *          @OA\JsonContent(
     *              @OA\Property(property="result_code", type="int", example="0", description="성공:0, 실패:-1"),
     *              @OA\Property(property="result_message", type="string", example="Success", description="성공:EMPTY, 실패:에러메세지(데이터 포맷 미 일치"),
     *              @OA\Property(property="result_data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="int", description="Id", example="Id"),
     *                      @OA\Property(property="name", type="string", description="이름", example="이름 1"),
     *                      @OA\Property(property="description", type="string", description="설명", example="설명 1"),
     *                      @OA\Property(property="data_json", type="string", description="JSON 문자열", example="{}"),
     *                  ),
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|max:128",
            "description" => "required|max:512",
            "data_json" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "result_code" => -1,
                "result_message" => $validator->errors(),
            ]);
        }

        try {
            $part = Part::create($request->all());
        } catch (\Exception $e) {
            Log::error("Database Save Fail: " . $e->getMessage());
            return response()->json([
                "result_code" => -1,
                "result_message" => "Database Save Fail",
            ]);
        }

        return response()->json([
            "result_code" => 0,
            "result_message" => "Success",
            "result_data" => [
                "id" => $part->id,
                "name" => $part->name,
                "description" => $part->description,
                "data_json" => $part->data_json,
            ],
        ]);
    }
}
