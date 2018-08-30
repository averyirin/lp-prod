(function(){
    'use strict';
    
    angular.module('portal').factory('Stars', function(){
        
        function Stars(value, maxValue) {
            this.stars = [];
            this.value = value;
            this.maxValue = maxValue;
            
            this.createStars(this.value);
        }
        
        Stars.prototype.createStars = function(value) {
            for(var i=0; i < this.maxValue; i++) {
                this.addStar({filled: i < value});
            }
        }
        
        Stars.prototype.updateStars = function(value) {
            for(var i=0; i < this.maxValue; i++) {
                this.stars[i].filled = i < value;
            }
        }
        
        Stars.prototype.addStar = function(star) {
            this.stars.push(star);
        };
        
        Stars.prototype.highlightStars = function(star) {
            //get index of star user is hovering over
            var index = this.getIndex(star);
            
            this.updateStars(index + 1);
        }
        
        Stars.prototype.unhighlightStars = function() {
            this.updateStars(this.value);
        }
        
        Stars.prototype.getIndex = function(star) {
            return this.stars.indexOf(star);
        }
        
        Stars.prototype.empty = function() {
            this.stars = [];
        }
        
        return Stars;
    });
})();