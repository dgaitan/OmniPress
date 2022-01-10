<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * App\Models\Organization
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $status
 * @property string $name
 * @property string|null $description
 * @property bool $is_default
 * @property int $owner_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $members
 * @property-read int|null $members_count
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Organization whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_default', 'status', 'owner_id'];

    /**
     * An organization has an owner
     */
    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Add member relations
     */
    public function members() {
        return $this->belongsToMany(
            User::class,
            'organization_members',
            'organization_id',
            'user_id'
        );
    }

    protected function getAccessPermissions(): array {
        $guest = ['can_see_basic', 'can_edit_profile'];
        $analyzer = ['can_see_analytics', 'can_see_insigths'];
        $manager = ['can_add_user', 'can_delete_user', 'can_send_invites'];
        $admin_perms = ['can_sync'];
        
        $roles = [
            'admin' => [...$admin_perms, ...$manager, ...$analyzer, ...$guest],
            'manager' => [...$manager, ...$analyzer, ...$guest],
            'analyzer' => [...$analyzer, ...$guest],
            'guest' => [...$guest]
        ];

        return $roles;
    }

    public function createRolesAndPermissions(): void {
        foreach ($this->getAccessPermissions() as $role => $perms) {
            $_role = Role::firstOrCreate(['name' => $role, 'team_id' => $this->id]);

            $_perms = [];
            foreach ($perms as $perm) {
                $_perms[] = Permission::firstOrCreate(['name' => $perm]);
            }

            $_role->syncPermissions($_perms);
        }
    }
}
