<?php

namespace App\Actions\Socialstream;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use JoelButcher\Socialstream\Contracts\CreatesConnectedAccounts;
use JoelButcher\Socialstream\Contracts\CreatesUserFromProvider;
use JoelButcher\Socialstream\Socialstream;
use Laravel\Jetstream\Jetstream;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Laravel\Socialite\Contracts\User as ProviderUserContract;

class CreateUserFromProvider implements CreatesUserFromProvider {
    /**
     * The creates connected accounts instance.
     *
     * @var \JoelButcher\Socialstream\Contracts\CreatesConnectedAccounts
     */
    public $createsConnectedAccounts;

    /**
     * Create a new action instance.
     *
     * @param  \JoelButcher\Socialstream\Contracts\CreatesConnectedAccounts  $createsConnectedAccounts
     */
    public function __construct(CreatesConnectedAccounts $createsConnectedAccounts) {
        $this->createsConnectedAccounts = $createsConnectedAccounts;
    }

    /**
     * Create a new user from a social provider user.
     *
     * @param  string  $provider
     * @param  ProviderUserContract  $providerUser
     * @return mixed
     */
    public function create(string $provider, ProviderUserContract $providerUser): mixed {
        return DB::transaction(function () use ($provider, $providerUser) {
            return tap(User::create([
                'name' => $providerUser->getName() ?? $providerUser->getNickname(),
                'email' => $providerUser->getEmail(),
            ]), function (User $user) use ($provider, $providerUser) {
                $user->markEmailAsVerified();
                $user->assignRole('kindhumans_team');

                if (Socialstream::hasProviderAvatarsFeature() && Jetstream::managesProfilePhotos() && $providerUser->getAvatar()) {
                    $user->setProfilePhotoFromUrl($providerUser->getAvatar());
                }

                $user->switchConnectedAccount(
                    $this->createsConnectedAccounts->create($user, $provider, $providerUser)
                );
            });
        });
    }
}
