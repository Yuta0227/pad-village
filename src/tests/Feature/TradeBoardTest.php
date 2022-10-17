<?php

namespace Tests\Feature;

use App\Http\Controllers\TradeBoardController;
use App\Models\Monster;
use App\Models\TradeBoardPost;
use App\Models\TradePostGive;
use App\Models\TradePostRequest;
use App\Models\User;
use Database\Seeders\MonstersTableSeeder;
use Database\Seeders\TradeBoardPostsTableSeeder;
use Database\Seeders\TradePostRequestsTableSeeder;
use Database\Seeders\TradePostGivesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Faker\Factory;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TradeBoardTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->authUser = User::factory()->create();
        $this->seed(MonstersTableSeeder::class);
        $this->seed(UsersTableSeeder::class);
        $this->seed(TradeBoardPostsTableSeeder::class);
        $this->seed(TradePostRequestsTableSeeder::class);
        $this->seed(TradePostGivesTableSeeder::class);
    }
    public function test_user_can_access_trade_board_timeline()
    {
        $this->get('/boards/trade')->assertStatus(200);
    }
    public function test_user_can_post_on_timeline()
    {
        $trade_board_post_count = TradeBoardPost::count();
        $trade_post_gives_count = TradePostGive::count();
        $trade_post_requests_count = TradePostRequest::count();
        //求1個出1個の投稿
        $first_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ]
        ];
        $first_monster_gives_array = [
            [
                'name' => 'ミズピィ',
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => null,
                'allow_show_pad_id_bool' => 0,
                'depth' => 0,
                'monster_requests' => $first_monster_requests_array,
                'monster_gives' => $first_monster_gives_array,
            ]
        );
        //投稿のカウントが1増える
        $trade_board_post_count++;
        //中身nullのものを排除する
        $trade_board_controller = new TradeBoardController();
        $first_monster_gives_array = $trade_board_controller->format_post($first_monster_gives_array);
        $first_monster_requests_array = $trade_board_controller->format_post($first_monster_requests_array);
        //monster_givesとmonster_requestsの数分カウントが増える
        $trade_post_gives_count = $trade_post_gives_count + count($first_monster_gives_array); //+1
        $trade_post_requests_count = $trade_post_requests_count + count($first_monster_requests_array); //+1
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求0出0の投稿
        $second_monster_requests_array = [
            [
                'name' => null,
                'amount' => null
            ]
        ];
        $second_monster_gives_array = [
            [
                'name' => null,
                'amount' => null
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => null,
                'allow_show_pad_id_bool' => 0,
                'depth' => 0,
                'monster_requests' => $second_monster_requests_array,
                'monster_gives' => $second_monster_gives_array,
            ]
        )->assertSessionHasErrors(['both_monster_requests_and_monster_gives_are_empty' => '出・求が両方とも空です']);
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求0出1の投稿
        $third_monster_requests_array = [
            [
                'name' => null,
                'amount' => null
            ]
        ];
        $third_monster_gives_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => null,
                'allow_show_pad_id_bool' => 0,
                'depth' => 0,
                'monster_requests' => $third_monster_requests_array,
                'monster_gives' => $third_monster_gives_array,
            ]
        );
        //投稿のカウントが1増える
        $trade_board_post_count++;
        //中身nullのものを排除する
        $trade_board_controller = new TradeBoardController();
        $third_monster_gives_array = $trade_board_controller->format_post($third_monster_gives_array);
        $third_monster_requests_array = $trade_board_controller->format_post($third_monster_requests_array);
        //monster_givesとmonster_requestsの数分カウントが増える。
        $trade_post_gives_count = $trade_post_gives_count + count($third_monster_gives_array); //+1
        $trade_post_requests_count = $trade_post_requests_count + count($third_monster_requests_array); //+0
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求1出0の投稿
        $fourth_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ]
        ];
        $fourth_monster_gives_array = [
            [
                'name' => null,
                'amount' => null
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => null,
                'allow_show_pad_id_bool' => 0,
                'depth' => 0,
                'monster_requests' => $fourth_monster_requests_array,
                'monster_gives' => $fourth_monster_gives_array,
            ]
        );
        //投稿のカウントが1増える
        $trade_board_post_count++;
        //中身nullのものを排除する
        $trade_board_controller = new TradeBoardController();
        $fourth_monster_gives_array = $trade_board_controller->format_post($fourth_monster_gives_array);
        $fourth_monster_requests_array = $trade_board_controller->format_post($fourth_monster_requests_array);
        //monster_givesとmonster_requestsの数分カウントが増える。
        $trade_post_gives_count = $trade_post_gives_count + count($fourth_monster_gives_array); //+0
        $trade_post_requests_count = $trade_post_requests_count + count($fourth_monster_requests_array); //+1
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求2出3の投稿
        $fifth_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ],
            [
                'name' => 'ミズピィ',
                'amount' => 1
            ]
        ];
        $fifth_monster_gives_array = [
            [
                'name' => 'モクピィ',
                'amount' => 1
            ],
            [
                'name' => 'ヒカピィ',
                'amount' => 1
            ],
            [
                'name' => 'ヤミピィ',
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => null,
                'allow_show_pad_id_bool' => 0,
                'depth' => 0,
                'monster_requests' => $fifth_monster_requests_array,
                'monster_gives' => $fifth_monster_gives_array,
            ]
        );
        //投稿のカウントが1増える
        $trade_board_post_count++;
        //中身nullのものを排除する
        $trade_board_controller = new TradeBoardController();
        $fifth_monster_gives_array = $trade_board_controller->format_post($fifth_monster_gives_array);
        $fifth_monster_requests_array = $trade_board_controller->format_post($fifth_monster_requests_array);
        //monster_givesとmonster_requestsの数分カウントが増える。
        $trade_post_gives_count = $trade_post_gives_count + count($fifth_monster_gives_array); //+3
        $trade_post_requests_count = $trade_post_requests_count + count($fifth_monster_requests_array); //+2
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求のnameが空でamountが入力あり
        $sixth_monster_requests_array = [
            [
                'name' => null,
                'amount' => 1
            ]
        ];
        $sixth_monster_gives_array = [
            [
                'name' => 'モクピィ',
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => null,
                'allow_show_pad_id_bool' => 0,
                'depth' => 0,
                'monster_requests' => $sixth_monster_requests_array,
                'monster_gives' => $sixth_monster_gives_array,
            ]
        )->assertSessionHasErrors();
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求のnameが入力ありでamountがnull
        $seventh_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => null
            ]
        ];
        $seventh_monster_gives_array = [
            [
                'name' => 'モクピィ',
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => null,
                'allow_show_pad_id_bool' => 0,
                'depth' => 0,
                'monster_requests' => $seventh_monster_requests_array,
                'monster_gives' => $seventh_monster_gives_array,
            ]
        )->assertSessionHasErrors();
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //出のnameが入力ありでamountがnull
        $eighth_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ]
        ];
        $eighth_monster_gives_array = [
            [
                'name' => 'モクピィ',
                'amount' => null
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => null,
                'allow_show_pad_id_bool' => 0,
                'depth' => 0,
                'monster_requests' => $eighth_monster_requests_array,
                'monster_gives' => $eighth_monster_gives_array,
            ]
        )->assertSessionHasErrors();
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //出のnameがnullでamountが入力あり
        $ninth_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ]
        ];
        $ninth_monster_gives_array = [
            [
                'name' => null,
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => null,
                'allow_show_pad_id_bool' => 0,
                'depth' => 0,
                'monster_requests' => $ninth_monster_requests_array,
                'monster_gives' => $ninth_monster_gives_array,
            ]
        )->assertSessionHasErrors();
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
    }
    public function test_user_can_access_trade_board_thread()
    {
        //タイムラインにある投稿を一つ取得
        $post_on_timeline_id = TradeBoardPost::where('depth', 0)->first()->id;
        //タイムラインにある投稿のスレッドにアクセス
        $this->get('/boards/trade/'.$post_on_timeline_id)->assertStatus(200);
    }
    public function test_user_can_post_on_thread()
    {
        $first_post_on_timeline_id = TradeBoardPost::where('depth', 0)->first()->id;
        $trade_board_post_count = TradeBoardPost::count();
        $trade_post_gives_count = TradePostGive::count();
        $trade_post_requests_count = TradePostRequest::count();
        //求1個出1個の投稿
        $first_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ]
        ];
        $first_monster_gives_array = [
            [
                'name' => 'ミズピィ',
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => $first_post_on_timeline_id,
                'allow_show_pad_id_bool' => 0,
                'depth' => 1,
                'monster_requests' => $first_monster_requests_array,
                'monster_gives' => $first_monster_gives_array,
            ]
        );
        //投稿のカウントが1増える
        $trade_board_post_count++;
        //中身nullのものを排除する
        $trade_board_controller = new TradeBoardController();
        $first_monster_gives_array = $trade_board_controller->format_post($first_monster_gives_array);
        $first_monster_requests_array = $trade_board_controller->format_post($first_monster_requests_array);
        //monster_givesとmonster_requestsの数分カウントが増える
        $trade_post_gives_count = $trade_post_gives_count + count($first_monster_gives_array); //+1
        $trade_post_requests_count = $trade_post_requests_count + count($first_monster_requests_array); //+1
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求0出0かつdescriptionがnull
        $second_monster_requests_array = [
            [
                'name' => null,
                'amount' => null
            ]
        ];
        $second_monster_gives_array = [
            [
                'name' => null,
                'amount' => null
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => null,
                'parent_trade_board_post_id' => $first_post_on_timeline_id,
                'allow_show_pad_id_bool' => 0,
                'depth' => 1,
                'monster_requests' => $second_monster_requests_array,
                'monster_gives' => $second_monster_gives_array,
            ]
        )->assertSessionHasErrors(['description' => '出・求・備考欄のどれか一つは入力してください']);
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求0出1の投稿
        $third_monster_requests_array = [
            [
                'name' => null,
                'amount' => null
            ]
        ];
        $third_monster_gives_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => null,
                'allow_show_pad_id_bool' => 0,
                'depth' => 1,
                'monster_requests' => $third_monster_requests_array,
                'monster_gives' => $third_monster_gives_array,
            ]
        );
        //投稿のカウントが1増える
        $trade_board_post_count++;
        //中身nullのものを排除する
        $trade_board_controller = new TradeBoardController();
        $third_monster_gives_array = $trade_board_controller->format_post($third_monster_gives_array);
        $third_monster_requests_array = $trade_board_controller->format_post($third_monster_requests_array);
        //monster_givesとmonster_requestsの数分カウントが増える。
        $trade_post_gives_count = $trade_post_gives_count + count($third_monster_gives_array); //+1
        $trade_post_requests_count = $trade_post_requests_count + count($third_monster_requests_array); //+0
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求1出0の投稿
        $fourth_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ]
        ];
        $fourth_monster_gives_array = [
            [
                'name' => null,
                'amount' => null
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => $first_post_on_timeline_id,
                'allow_show_pad_id_bool' => 0,
                'depth' => 1,
                'monster_requests' => $fourth_monster_requests_array,
                'monster_gives' => $fourth_monster_gives_array,
            ]
        );
        //投稿のカウントが1増える
        $trade_board_post_count++;
        //中身nullのものを排除する
        $trade_board_controller = new TradeBoardController();
        $fourth_monster_gives_array = $trade_board_controller->format_post($fourth_monster_gives_array);
        $fourth_monster_requests_array = $trade_board_controller->format_post($fourth_monster_requests_array);
        //monster_givesとmonster_requestsの数分カウントが増える。
        $trade_post_gives_count = $trade_post_gives_count + count($fourth_monster_gives_array); //+0
        $trade_post_requests_count = $trade_post_requests_count + count($fourth_monster_requests_array); //+1
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求2出3の投稿
        $fifth_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ],
            [
                'name' => 'ミズピィ',
                'amount' => 1
            ]
        ];
        $fifth_monster_gives_array = [
            [
                'name' => 'モクピィ',
                'amount' => 1
            ],
            [
                'name' => 'ヒカピィ',
                'amount' => 1
            ],
            [
                'name' => 'ヤミピィ',
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => $first_post_on_timeline_id,
                'allow_show_pad_id_bool' => 0,
                'depth' => 1,
                'monster_requests' => $fifth_monster_requests_array,
                'monster_gives' => $fifth_monster_gives_array,
            ]
        );
        //投稿のカウントが1増える
        $trade_board_post_count++;
        //中身nullのものを排除する
        $trade_board_controller = new TradeBoardController();
        $fifth_monster_gives_array = $trade_board_controller->format_post($fifth_monster_gives_array);
        $fifth_monster_requests_array = $trade_board_controller->format_post($fifth_monster_requests_array);
        //monster_givesとmonster_requestsの数分カウントが増える。
        $trade_post_gives_count = $trade_post_gives_count + count($fifth_monster_gives_array); //+3
        $trade_post_requests_count = $trade_post_requests_count + count($fifth_monster_requests_array); //+2
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求のnameが空でamountが入力あり
        $sixth_monster_requests_array = [
            [
                'name' => null,
                'amount' => 1
            ]
        ];
        $sixth_monster_gives_array = [
            [
                'name' => 'モクピィ',
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => $first_post_on_timeline_id,
                'allow_show_pad_id_bool' => 0,
                'depth' => 1,
                'monster_requests' => $sixth_monster_requests_array,
                'monster_gives' => $sixth_monster_gives_array,
            ]
        )->assertSessionHasErrors();
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求のnameが入力ありでamountがnull
        $seventh_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => null
            ]
        ];
        $seventh_monster_gives_array = [
            [
                'name' => 'モクピィ',
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => $first_post_on_timeline_id,
                'allow_show_pad_id_bool' => 0,
                'depth' => 1,
                'monster_requests' => $seventh_monster_requests_array,
                'monster_gives' => $seventh_monster_gives_array,
            ]
        )->assertSessionHasErrors();
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //出のnameが入力ありでamountがnull
        $eighth_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ]
        ];
        $eighth_monster_gives_array = [
            [
                'name' => 'モクピィ',
                'amount' => null
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => $first_post_on_timeline_id,
                'allow_show_pad_id_bool' => 0,
                'depth' => 1,
                'monster_requests' => $eighth_monster_requests_array,
                'monster_gives' => $eighth_monster_gives_array,
            ]
        )->assertSessionHasErrors();
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //出のnameがnullでamountが入力あり
        $ninth_monster_requests_array = [
            [
                'name' => 'ホノピィ',
                'amount' => 1
            ]
        ];
        $ninth_monster_gives_array = [
            [
                'name' => null,
                'amount' => 1
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => $first_post_on_timeline_id,
                'allow_show_pad_id_bool' => 0,
                'depth' => 1,
                'monster_requests' => $ninth_monster_requests_array,
                'monster_gives' => $ninth_monster_gives_array,
            ]
        )->assertSessionHasErrors();
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
        //求0出0かつdescriptionが入力あり
        $second_monster_requests_array = [
            [
                'name' => null,
                'amount' => null
            ]
        ];
        $second_monster_gives_array = [
            [
                'name' => null,
                'amount' => null
            ]
        ];
        $this->actingAs($this->authUser)->post(
            '/boards/trade',
            [
                'description' => 'test description',
                'parent_trade_board_post_id' => $first_post_on_timeline_id,
                'allow_show_pad_id_bool' => 0,
                'depth' => 1,
                'monster_requests' => $second_monster_requests_array,
                'monster_gives' => $second_monster_gives_array,
            ]
        );
        //投稿のカウントが1増える
        $trade_board_post_count++;
        //カウントと実際のテーブルのカウントが一致していれば正常
        $this->assertDatabaseCount('trade_board_posts', $trade_board_post_count);
        $this->assertDatabaseCount('trade_post_gives', $trade_post_gives_count);
        $this->assertDatabaseCount('trade_post_requests', $trade_post_requests_count);
    }
}
