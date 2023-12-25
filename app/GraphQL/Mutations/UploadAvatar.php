<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class UploadAvatar
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $args = $args['input'];
        $avatar = $args['avatar'];

        $extension = $avatar->getClientOriginalExtension();
        $name = $avatar->getClientOriginalName();

        $name = Str::replaceLast(".$extension", '', $name);
        $uploadOn = "public/avatars";

        $baseName = Str::replace(' ', '-', $name);

        $duplicate = 0;
        while (File::exists($uploadOn . '/' . "$baseName.$extension")) {
            $duplicate++;
            $baseName = $baseName . '(' . (string) $duplicate . ')';
        }

        Storage::putFileAs($uploadOn, $avatar, "$baseName.$extension");
        $path = Storage::url("$uploadOn/$baseName.$extension");

        $user = User::find(auth('sanctum')->id());
        $user->update(['avatar' => $path]);

        return $user;
    }
}
