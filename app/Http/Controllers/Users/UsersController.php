<?php

namespace App\Http\Controllers\Users;

use App\DataObjects\Repositories\Users\CreateUserData;
use App\DataObjects\Repositories\Users\UpdateUserData;
use App\DataObjects\Repositories\Users\UpdateUserEmailData;
use App\DataObjects\Repositories\Users\UpdateUserPasswordData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Interfaces\Users\UsersInterface;
use App\Jobs\SendEmailVerificationOnEmailChangeJob;
use App\Jobs\SendNewUserWelcomeEmailJob;
use App\Jobs\SendPasswordChangeNotificationJob;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Joalvm\Utils\Facades\Response;

class UsersController extends Controller
{
    public function __construct(protected UsersInterface $repository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return Response::collection(
            $this->repository
                ->setPersons($request->get('persons'))
                ->setRoles($request->get('roles'))
                ->all()
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = CreateUserData::from($request->post());

        $userModel = $this->repository->create($data);

        dispatch(
            new SendNewUserWelcomeEmailJob(
                $userModel,
                $request->input('redirect_url')
            )
        );

        return Response::stored($this->repository->find($userModel->id));
    }

    public function show($id): JsonResponse
    {
        return Response::item($this->repository->find($id));
    }

    public function update($id, UpdateUserRequest $request): JsonResponse
    {
        $data = UpdateUserData::from($request->only(['avatar_url', 'enabled']));

        $userModel = $this->repository->update($id, $data);

        $this->handleUpdateEmail($request, $userModel);
        $this->handleUpdatePassword($request, $userModel);

        return Response::updated($this->repository->find($userModel->id));
    }

    public function destroy($id): JsonResponse
    {
        return Response::destroyed($this->repository->delete($id));
    }

    private function handleUpdateEmail(UpdateUserRequest $request, User $userModel)
    {
        if (!$request->has('email')) {
            return;
        }

        $data = UpdateUserEmailData::from($request->post('email'));

        $data->host = $request->getHost();

        $userModel = $this->repository->updateEmail($userModel->id, $data);

        if ($userModel->isEmailChanged) {
            dispatch(
                new SendEmailVerificationOnEmailChangeJob(
                    $userModel,
                    $request->input('redirect_url')
                )
            );
        }
    }

    private function handleUpdatePassword(UpdateUserRequest $request, User $userModel)
    {
        if (!$request->has('password')) {
            return;
        }

        $input = $request->only([
            'current_password',
            'password',
            'confirm_password',
        ]);

        $data = UpdateUserPasswordData::from($input);

        $userModel = $this->repository->updatePassword($userModel->id, $data);

        dispatch(
            new SendPasswordChangeNotificationJob(
                $userModel,
                $request->input('redirect_url')
            )
        );
    }
}
