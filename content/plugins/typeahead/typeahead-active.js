var substringMatcher = function(strs) {
return function findMatches(q, cb) {
  var matches, substrRegex;

  // an array that will be populated with substring matches
  matches = [];

  // regex used to determine if a string contains the substring `q`
  substrRegex = new RegExp(q, 'i');

  // iterate through the pool of strings and for any string that
  // contains the substring `q`, add it to the `matches` array
  $.each(strs, function(i, str) {
    if (substrRegex.test(str)) {
      matches.push(str);
    }
  });

  cb(matches);
};
};

var states = ['I think being in love with life is a key to eternal youth.” —Doug Hutchison', 'You’re only here for a short visit. Don’t hurry, don’t worry. And be sure to smell the flowers along the way.” —Walter Hagen',  'A man who dares to waste one hour of time has not discovered the value of life.” —Charles Darwin',  'If life were predictable it would cease to be life, and be without flavor.” —Eleanor Roosevelt',  'All life is an experiment. The more experiments you make the better.” —Ralph Waldo Emerson',  'All of life is peaks and valleys. Don’t let the peaks get too high and the valleys too low.” —John Wooden',  'Find ecstasy in life; the mere sense of living is joy enough.” —Emily Dickinson',  '“My mission in life is not merely to survive, but to thrive; and to do so with some passion, some compassion, some humor, and some style.” —Maya Angelou',  'However difficult life may seem, there is always something you can do and succeed at.” —Stephen Hawking',  'Life is like riding a bicycle. To keep your balance, you must keep moving.” —Albert Einstein'
];

$('.typeahead').typeahead({
hint: true,
highlight: true,
minLength: 1
},
{
name: 'states',
source: substringMatcher(states)
});