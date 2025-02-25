<?php

namespace App\Http\Controllers\api;

use App\Events\UserCreatedEvent;
use App\Exceptions\UserCreationException;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\UserResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            //create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            //create wallet
            $user->wallets()->create([
                'balance' => $request->balance
            ]);
            //send an email to user
            event(new UserCreatedEvent($user->load('wallets')));

            DB::commit();

            return response()->json(new UserResource($user->load('wallets')));

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error("User creation error: " . $exception->getMessage());
            throw new UserCreationException($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::with('wallets')->where('id', $id)->first();
            if (!$user) {
                throw new UserNotFoundException();
            }
            return new UserResource($user);

        } catch (UserNotFoundException $exception) {
            DB::rollBack();
            Log::error("Error : " . $exception->getMessage());
            return $exception->render(); // Return the error response

        } catch (\Exception $exception) {
            Log::error("Unexpected error: " . $exception->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * get user transactions
     * @param $id
     */
    public function getTransactions($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                throw new UserNotFoundException();
            }
            $transactions = Transaction::where("user_id", $id)->orderBy('created_at', 'desc')->paginate(10);
            return TransactionResource::collection($transactions);
        } catch (UserNotFoundException $exception) {
            DB::rollBack();
            Log::error("Error : " . $exception->getMessage());
            return $exception->render(); // Return the error response

        } catch (\Exception $exception) {
            Log::error("Unexpected error: " . $exception->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }

    }

}
