<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles; // ✅ Add this
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // ✅ Add HasRoles here

    protected $fillable = [
        'employee_id',
        'item_number_id',
        'first_name',
        'middle_name',
        'last_name',
        'deactivation_reason',
        'extension_name',
        'employment_status_id',
        'division_id',
        'section_id',
        'username',
        'email',
        'password',
        'gender',
        'role',
        'citizenship',
        'dual_citizenship',
        'dual_citizenship_type',

        // Permanent address fields
        'perm_region',
        'perm_province',
        'perm_city',
        'perm_barangay',

        // Residential address fields
        'res_region',
        'res_province',
        'res_city',
        'res_barangay',
    ];

    protected $hidden = [
        'password',
    ];
    
      protected $dates = [
        'birthday', // make sure birthday is cast to date
    ];

    // Accessor for age
    public function getAgeAttribute()
    {
        return $this->birthday ? Carbon::parse($this->birthday)->age : null;
    }

    /* ================================================
     |  RELATIONSHIPS
     ================================================= */

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function employmentStatus()
    {
        return $this->belongsTo(EmploymentStatus::class, 'employment_status_id');
    }

    public function itemNumber()
    {
        return $this->belongsTo(ItemNumber::class);
    }

    /* ----- Address Relationships: Permanent ----- */
    public function permRegion()
    {
        return $this->belongsTo(Region::class, 'perm_region', 'psgc');
    }

    public function permProvince()
    {
        return $this->belongsTo(Province::class, 'perm_province', 'psgc');
    }

    public function permCity()
    {
        return $this->belongsTo(Municipality::class, 'perm_city', 'psgc');
    }

    public function permBarangay()
    {
        return $this->belongsTo(Barangay::class, 'perm_barangay', 'psgc');
    }

    /* ----- Address Relationships: Residential ----- */
    public function resRegion()
    {
        return $this->belongsTo(Region::class, 'res_region', 'psgc');
    }

    public function resProvince()
    {
        return $this->belongsTo(Province::class, 'res_province', 'psgc');
    }

    public function resCity()
    {
        return $this->belongsTo(Municipality::class, 'res_city', 'psgc');
    }

    public function resBarangay()
    {
        return $this->belongsTo(Barangay::class, 'res_barangay', 'psgc');
    }

    /* ----- PDS Sections (Family, IDs, Educ, etc.) ----- */
    public function governmentIds()
    {
        return $this->hasMany(GovernmentId::class, 'user_id', 'id');
    }

    public function familyBackgrounds()
    {
        return $this->hasMany(FamilyBackground::class, 'employee_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Child::class, 'family_background_id');
    }

    public function educations()
    {
        return $this->hasMany(Education::class, 'user_id', 'id');
    }

    public function csEligibilities()
    {
        return $this->hasMany(CsEligibility::class, 'user_id', 'id');
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class, 'user_id', 'id');
    }

    public function voluntaryWorks()
    {
        return $this->hasMany(VoluntaryWork::class, 'user_id', 'id');
    }
    public function learningAndDevelopments()
    {
        return $this->hasMany(LearningandDevelopment::class, 'user_id', 'id');
    }
    public function Skills()
    {
        return $this->hasMany(Skill::class, 'user_id', 'id');
    }
    public function nonAcademics()
    {
        return $this->hasMany(NonAcademic::class, 'user_id', 'id');
    }
    public function organizations()
    {
        return $this->hasMany(Organization::class, 'user_id', 'id');
    }
    public function references()
    {
        return $this->hasMany(Reference::class, 'user_id', 'id');
    }
    public function otherInformations()
    {
        return $this->belongsTo(OtherInformation::class, 'user_id', 'id');
    }    
    
}