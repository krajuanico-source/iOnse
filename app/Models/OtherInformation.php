<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherInformation extends Model
{
    use HasFactory;

    protected $table = 'other_information'; // ✅ Explicit table name (optional but recommended)
    
    protected $fillable = [
        'user_id',
        'related_within_third_degree',
        'related_within_fourth_degree',
        'related_within_fourth_degree_details',
        'found_guilty_admin_offense',
        'administrative_offense_details',
        'criminally_charged',
        'criminal_date_filed',
        'criminal_status',
        'convicted_of_crime',
        'crime_details',
        'separated_from_service',
        'service_separation_details',
        'candidate_in_election',
        'candidate_in_election_details',
        'resigned_before_election',
        'resigned_before_election_details',
        'immigrant_status',
        'immigrant_country',
        'member_of_indigenous_group',
        'indigenous_group_details',
        'person_with_disability',
        'disability_details',
        'solo_parent',
        'solo_parent_details',
    ];

    protected $casts = [
        'criminal_date_filed' => 'date', // ✅ Automatically cast to Carbon instance
    ];

    /**
     * Relationship: Each "OtherInformation" belongs to one user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
