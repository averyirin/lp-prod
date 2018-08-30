(function(){
    'use strict';
    
    angular.module('portal').factory('Rating', function(Stars){
        
        function Rating(ratings, maxValue, userId) {
            ratings ? this.ratings = ratings : this.ratings=[];
            this.stars = [];
            this.maxValue = maxValue;
            this.userId = userId;
            this.totalValue = 0;
            this.numOfRatings = 0;
            
            this.createRating();
        }
        
        Rating.prototype.createRating = function() {
            var userValue = 0;
            
            if(this.ratings.length >= 1) {
                var totalRatings = 0;

                for(var i=0; i < this.ratings.length; i++) {
                    var rating = this.ratings[i];
                    totalRatings += rating.value;
                    this.numOfRatings += 1;
                    
                    if(rating.user == this.userId) {
                        userValue = rating.value;
                    }
                }

                this.totalValue = totalRatings/this.numOfRatings;
            }
            
            this.stars = new Stars(userValue, this.maxValue);
            //this.stars = stars.stars;
        };
        
        Rating.prototype.update = function() {
            this.empty();
            this.createRating();
        };
        
        Rating.prototype.addRating = function(star, userId) {
            var index = this.stars.stars.indexOf(star);
            var value = index + 1;
            
            for(var i=0; i < this.ratings.length; i++) {
                var rating = this.ratings[i];
                
                if(userId == rating.user) {
                    this.ratings.splice(i, 1);
                    i -= 1;
                }
            }
            
            var rating = {value: value, user: userId}
            this.ratings.push(rating);
        }
        
        Rating.prototype.getLatestRating = function() {
            var rating = this.ratings[this.ratings.length -1];
            
            return rating;
        }
        
        Rating.prototype.empty = function() {
            this.totalValue = 0;
            this.userValue = 0;
            this.numOfRatings = 0;
            this.stars.empty();
        }
        
        return Rating;
    });
})();