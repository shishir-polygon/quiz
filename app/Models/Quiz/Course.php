<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function scopeSearch($query, string $searchTerm)
    {
        return $query->where('name', 'LIKE', "%$searchTerm%");
    }

    // Relation
    public function courseLevels()
    {
        return $this->hasMany(CourseLevel::class, 'course_id');
    }
}
