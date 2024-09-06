function autocomplete(inputName) {
    const input = document.querySelector(`input[name="${inputName}"]`);

    document.addEventListener('click', function (event) {
        closeList();
    });
    input.addEventListener('input', function () {
        closeList();

        // If the input is empty or less than a certain length, exit the function
        if (!this.value || this.value.length < 1) {
            return;
        }

        // Fetch data from the API based on the input value
        fetch(`/api/search?term=${encodeURIComponent(this.value)}`)
            .then(response => response.json())
            .then(data => {
                // Create a suggestions <div> and add it to the element containing the input field
                suggestions = document.createElement('ul');
                // set background color
                suggestions.style.backgroundColor = '#4f565a';
                suggestions.setAttribute('id', 'suggestions');
                // set class
                suggestions.classList.add('select2-results__options');

                this.parentNode.appendChild(suggestions);

                // Iterate through the retrieved data and create suggestion elements
                data.forEach(item => {
                    let suggestion = document.createElement('li');
                    suggestion.innerHTML = item;
                    suggestion.addEventListener('click', function () {
                        input.value = this.innerHTML;
                        closeList();
                    });
                    suggestion.style.cursor = 'pointer';
                    // set text color
                    suggestion.style.color = 'white';
                    suggestions.appendChild(suggestion);
                    suggestion.classList.add('select2-results__option');
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    });

    function closeList() {
        let suggestions = document.getElementById('suggestions');
        if (suggestions) {
            suggestions.parentNode.removeChild(suggestions);
        }
    }
}

// Call the autocomplete function with the input field name attribute value
autocomplete('device_name');