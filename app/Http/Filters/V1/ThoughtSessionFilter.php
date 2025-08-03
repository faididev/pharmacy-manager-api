<?php

namespace App\Http\Filters\V1;

use App\Enums\ThoughtDomain;
use App\Enums\ThoughtSessionStage;
use App\Enums\ThoughtSessionStatus;

class ThoughtSessionFilter extends QueryFilter 
{
    protected $sortable = [
        'domain',
        'status',
        'intensity',
        'currentStage' => 'current_stage',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    /**
     * Filter by domain (work, self, relationship, future)
     * Usage: ?domain=work or ?domain=work,self
     */
    public function domain($value) 
    {
        $domains = explode(',', $value);
        
        // Validate that all domains are valid enum values
        $validDomains = collect($domains)->filter(function($domain) {
            return ThoughtDomain::tryFrom($domain) !== null;
        });

        if ($validDomains->isNotEmpty()) {
            return $this->builder->whereIn('domain', $validDomains->toArray());
        }

        return $this->builder;
    }

    /**
     * Filter by status (draft, pending, completed, canceled)
     * Usage: ?status=completed or ?status=pending,completed
     */
    public function status($value) 
    {
        $statuses = explode(',', $value);
        
        // Validate that all statuses are valid enum values
        $validStatuses = collect($statuses)->filter(function($status) {
            return ThoughtSessionStatus::tryFrom($status) !== null;
        });

        if ($validStatuses->isNotEmpty()) {
            return $this->builder->whereIn('status', $validStatuses->toArray());
        }

        return $this->builder;
    }

    /**
     * Filter by current stage (1-5)
     * Usage: ?stage=1 or ?stage=1,2,3
     */
    public function stage($value) 
    {
        $stages = explode(',', $value);
        
        // Convert to integers and validate
        $validStages = collect($stages)->map(function($stage) {
            return (int) $stage;
        })->filter(function($stage) {
            return ThoughtSessionStage::tryFrom($stage) !== null;
        });

        if ($validStages->isNotEmpty()) {
            return $this->builder->whereIn('current_stage', $validStages->toArray());
        }

        return $this->builder;
    }

    /**
     * Filter by intensity range
     * Usage: ?intensity=5 or ?intensity=5,8 (between 5 and 8)
     */
    public function intensity($value) 
    {
        $intensities = explode(',', $value);

        if (count($intensities) > 1) {
            // Range filter
            $min = max(0, min(100, (int) $intensities[0]));
            $max = max(0, min(100, (int) $intensities[1]));
            
            return $this->builder->whereBetween('intensity', [$min, $max]);
        }

        // Exact match
        $intensity = max(0, min(100, (int) $value));
        return $this->builder->where('intensity', $intensity);
    }

    /**
     * Filter by creation date
     * Usage: ?createdAt=2025-06-01 or ?createdAt=2025-06-01,2025-06-30
     */
    public function createdAt($value) 
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    /**
     * Filter by update date
     * Usage: ?updatedAt=2025-06-01 or ?updatedAt=2025-06-01,2025-06-30
     */
    public function updatedAt($value) 
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }

    /**
     * Search in negative thoughts
     * Usage: ?search=فاشل or ?search=*وظيفة*
     */
    public function search($value) 
    {
        $searchTerm = str_replace('*', '%', $value);
        
        return $this->builder->where(function($query) use ($searchTerm) {
            $query->where('negative_thought', 'like', "%{$searchTerm}%")
                  ->orWhere('impact', 'like', "%{$searchTerm}%")
                  ->orWhere('trigger', 'like', "%{$searchTerm}%")
                  ->orWhere('reframed_thought', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Filter by emotions
     * Usage: ?emotions=anxious or ?emotions=anxious,sad
     */
    public function emotions($value) 
    {
        $emotions = explode(',', $value);
        
        return $this->builder->where(function($query) use ($emotions) {
            foreach ($emotions as $emotion) {
                $query->orWhereJsonContains('emotions', $emotion);
            }
        });
    }

    /**
     * Include relationships
     * Usage: ?include=user
     */
    public function include($value) 
    {
        $relationships = explode(',', $value);
        $allowedRelationships = ['user']; // Add more as needed
        
        $validRelationships = array_intersect($relationships, $allowedRelationships);
        
        if (!empty($validRelationships)) {
            return $this->builder->with($validRelationships);
        }

        return $this->builder;
    }

    /**
     * Filter completed sessions (stage = COMPLETED and status = completed)
     * Usage: ?completed=true or ?completed=1
     */
    public function completed($value) 
    {
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
            return $this->builder->where('current_stage', ThoughtSessionStage::COMPLETED->value)
                                 ->where('status', ThoughtSessionStatus::COMPLETED->value);
        }

        return $this->builder;
    }

    /**
     * Filter incomplete sessions (not completed)
     * Usage: ?incomplete=true or ?incomplete=1
     */
    public function incomplete($value) 
    {
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
            return $this->builder->where(function($query) {
                $query->where('current_stage', '!=', ThoughtSessionStage::COMPLETED->value)
                      ->orWhere('status', '!=', ThoughtSessionStatus::COMPLETED->value);
            });
        }

        return $this->builder;
    }

    /**
     * Filter draft sessions
     * Usage: ?draft=true or ?draft=1
     */
    public function draft($value) 
    {
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
            return $this->builder->where('status', ThoughtSessionStatus::DRAFT->value);
        }

        return $this->builder;
    }
}