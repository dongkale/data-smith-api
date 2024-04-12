<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Item;

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
 *     url="/api/item",
 * ),
 */
class ItemController extends Controller
{
    /**
     * @OA\Get (
     *     path="/test",
     *     tags={"[TEST] API Get 테스트"},
     *     summary="API Get 테스트",
     *     description="API Get 테스트",
     *     @OA\Parameter(
     *         description="요청 값",
     *         in="query",
     *         name="param",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="1", summary="paramter"),
     *     ),
     *     @OA\Parameter(
     *         description="요청 값들",
     *         in="query",
     *         name="params",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="string", value="[1,2,3]", summary="paramters"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="결과값",
     *         @OA\JsonContent(
     *             @OA\Property(property="result_code", type="int", example="0", description="성공:0, 실패:-1"),
     *             @OA\Property(property="result_message", type="string", example="", description="성공:EMPTY, 실패:에러메세지"),
     *             @OA\Property(property="result_data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="param", type="string", description="요청 param", example="1"),
     *                      @OA\Property(property="params", type="string", description="요청 param", example="[1,2,3]"),
     *                  ),
     *            )
     *         )
     *     )
     * )
     */
    public function testGet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "param" => "required",
            "params" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "result_code" => -1,
                "result_message" => $validator->errors(),
            ]);
        }

        $param = $request->param;
        $params = $request->params;

        return response()->json([
            "result_code" => 0,
            "result_message" => "Success",
            "result_data" => ["param" => $param, "params" => $params],
        ]);
    }

    /**
     * @OA\Post (
     *     path="/testPost",
     *     tags={"[TEST] API Post 테스트"},
     *     summary="API Post 테스트",
     *     description="API Post 테스트",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *              @OA\Property(property="param", type="string", example="1", description="요청 값"),
     *              @OA\Property(property="params", type="string", example="[1,2,3]", description="요청 값들")     *
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="결과값",
     *         @OA\JsonContent(
     *             @OA\Property(property="result_code", type="int", example="0", description="성공:0, 실패:-1"),
     *             @OA\Property(property="result_message", type="string", example="", description="성공:Success, 실패:에러메세지"),
     *             @OA\Property(property="result_data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="param", type="string", description="요청 param", example="1"),
     *                      @OA\Property(property="params", type="string", description="요청 param", example="[1,2,3]"),
     *                  ),
     *            )
     *         )
     *     )
     * )
     */
    public function testPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "param" => "required",
            "params" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "result_code" => -1,
                "result_message" => $validator->errors(),
            ]);
        }

        $param = $request->param;
        $params = $request->params;

        return response()->json([
            "result_code" => 0,
            "result_message" => "Success",
            "result_data" => ["param" => $param, "params" => $params],
        ]);
    }

    /**
     * @OA\Get (
     *     path="/items",
     *     summary="",
     *     tags={"- 전체 리스트 요청"},
     *     description="전체 리스트 요청",
     *     @OA\Response(
     *         response="200",
     *         description="결과값",
     *         @OA\JsonContent(
     *             @OA\Property(property="result_code", type="int", example="0", description="성공:0, 실패:-1"),
     *             @OA\Property(property="result_message", type="string", example="", description="성공:Success, 실패:에러메세지"),
     *             @OA\Property(property="result_data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="name", type="string", description="이름", example="sample_1"),
     *                      @OA\Property(property="description", type="string", description="설명", example="설명 1"),
     *                      @OA\Property(property="data_json", type="string", description="json string", example="{'number':1, 'string':'test'}"),
     *                  ),
     *                  @OA\Items(
     *                      @OA\Property(property="name", type="string", description="이름", example="sample_2"),
     *                      @OA\Property(property="description", type="string", description="설명", example="설명 2"),
     *                      @OA\Property(property="data_json", type="string", description="json string", example="{'number':2, 'string':'test'}"),
     *                  ),
     *            )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $items = Item::all();

        $data = [];
        foreach ($items as $item) {
            $data[] = [
                "name" => $item->name,
                "description" => $item->description,
                "data_json" => $item->data_json,
            ];
        }

        return response()->json([
                "result_code" => 0,
                "result_message" => 'Success',
                "result_data" => $data
            ]);        
    }    

    /**
     * @OA\Get (
     *     path="/items/{id}",
     *     summary="",
     *     tags={"- 지정 아이템 요청"},
     *     description="지정 아이템 요청",
     *     @OA\Response(
     *          response="200",
     *          description="결과값",
     *          @OA\JsonContent(
     *              @OA\Property(property="result_code", type="int", example="0", description="성공:0, 실패:-1"),
     *              @OA\Property(property="result_message", type="string", example="", description="성공:Success, 실패:아이템 존재 하지 않음"),
     *              @OA\Property(property="result_data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="name", type="string", description="이름", example="sample_1"),
     *                      @OA\Property(property="description", type="string", description="설명", example="설명 1"),
     *                      @OA\Property(property="data_json", type="string", description="json string", example="{'number':1, 'string':'test'}"),
     *                  ),
     *             )
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $item = Item::where('id', '=', $id)->first();    
        if (empty($item)) {
            return response()->json([
                "result_code" => -1,
                "result_message" => 'Item not found',
            ]);
        }
        
        return response()->json([
                "result_code" => 0,
                "result_message" => 'Success',
                "result_data" => [
                    "name" => $item->name,
                    "description" => $item->description,
                    "data_json" => $item->data_json,
                ],
            ]);     
    }

    /**
     * @OA\Get (
     *     path="/showByName/{name}",
     *     summary="",
     *     tags={"- 지정 아이템 요청(name 지정)"},
     *     description="지정 아이템 요청",
     *     @OA\Response(
     *          response="200",
     *          description="결과값",
     *          @OA\JsonContent(
     *              @OA\Property(property="result_code", type="int", example="0", description="성공:0, 실패:-1"),
     *              @OA\Property(property="result_message", type="string", example="", description="성공:Success, 실패:아이템 존재 하지 않음"),
     *              @OA\Property(property="result_data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="name", type="string", description="이름", example="sample_1"),
     *                      @OA\Property(property="description", type="string", description="설명", example="설명 1"),
     *                      @OA\Property(property="data_json", type="string", description="json string", example="{'number':1, 'string':'test'}"),
     *                  ),
     *             )
     *         )
     *     )
     * )
     */
    public function showByName($name)
    {
        $item = Item::where('name', '=', $name)->first();    
        if (empty($item)) {
            return response()->json([
                "result_code" => -1,
                "result_message" => 'Item not found',
            ]);
        }
        
        return response()->json([
                "result_code" => 0,
                "result_message" => 'Success',
                "result_data" => [
                    "name" => $item->name,
                    "description" => $item->description,
                    "data_json" => $item->data_json,
                ],
            ]);     
    }

    /**
     * @OA\Post (
     *     path="/items",
     *     summary="",
     *     tags={"- 아이템 저장"},
     *     description="아이템 저장, 지정 이름, 설명을 json data로 요청하면 저장한다",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="sample_1", description="이름"),
     *              @OA\Property(property="description", type="string", example="설명 1", description="설명"),
     *              @OA\Property(property="data_json", type="string", example="{'number':1, 'string':'test'}", description="json string")
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="결과값",
     *          @OA\JsonContent(
     *              @OA\Property(property="result_code", type="int", example="0", description="성공:0, 실패:-1"),
     *              @OA\Property(property="result_message", type="string", example="", description="성공:EMPTY, 실패:에러메세지(데이터 포맷 미 일치"),
     *              @OA\Property(property="result_data", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="name", type="string", description="이름", example="sample_1"),
     *                      @OA\Property(property="description", type="string", description="설명", example="설명 1"),
     *                      @OA\Property(property="data_json", type="string", description="json string", example="{'number':1, 'string':'test'}"),
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
          "data_json" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "result_code" => -1,
                "result_message" => $validator->errors(),
            ]);
        }
        
        $item = Item::create($request->all()); 
            
        return response()->json([
                "result_code" => 0,
                "result_message" => 'Success',
                "result_data" => [
                    "name" => $item->name,
                    "description" => $item->description,
                    "data_json" => $item->data_json,
                ],
            ]);        
    }
}
