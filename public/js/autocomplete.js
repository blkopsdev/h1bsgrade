var employer = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	remote: {
	    url: '/autocomplete-employer?query=%QUERY%',
	    wildcard: '%QUERY'
  	}
});

$('#employer').typeahead({
	hint: false,
	highlight: false,
	minLength: 1,
}, 
{
	name: 'employer',
	limit: 10,
	source: employer,
	display: function(data) {
        return data.name
    },
});

// [START JOB TITLE]
var job = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	remote: {
	    url: '/autocomplete-job-title?query=%QUERY%',
	    wildcard: '%QUERY'
  	}
});

$('#job').typeahead({
	hint: false,
	highlight: false,
	minLength: 1
}, 
{
	name: 'job',
	limit: 10,
	source: job,
	display: function(data) {
        return data.name
    },
});
// [END JOB TITLE]


// [START CITY]
var city = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	remote: {
	    url: '/autocomplete-city?query=%QUERY%',
	    wildcard: '%QUERY'
  	}
});

$('#city').typeahead({
	hint: false,
	highlight: false,
	minLength: 1
}, 
{
	name: 'city',
	limit: 10,
	source: city,
	display: function(data) {
        return data.name
    },
});
// [END CITY]