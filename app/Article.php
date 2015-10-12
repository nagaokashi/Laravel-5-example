<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Article extends Model {

    protected $fillable = [
        'title',
        'body',
        'published_at',
        'user_id'   //temporary
    ];
    
    protected $dates = ['published_at'];
    
    public function scopePublished($query) {
        $query->where('published_at', '<=', Carbon::now());
    }
        
    public function scopeUnPublished($query) {
        $query->where('published_at', '>', Carbon::now());
    }


    /**
     * Get the Published_at attribute
     *
     * @param $date
     */
    public function setPublishedAtAttribute($date){
        
        $this->attributes['published_at'] = Carbon::parse($date);
                
    }

    public function getPublishedAtAttribute($date){
        return Carbon::parse($date)->format('Y-m-d');
    }
    
    /**
     * An article is owned by a user
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the tags  associated with the given article
     */
    public function tags(){
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }

    /**
     * Get tat ca cac Tag ID ma da tich hop voi Article nay
     * Cach goi ham nay: tag_list
     * @return array
     */
    public function getTagListAttribute(){
        return $this->tags->lists('id');
    }
}
