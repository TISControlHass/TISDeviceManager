@extends(backpack_view('blank'))

@section('content')
    @component('components.dashboard_control_div')
    @endcomponent
@endsection


@section('scripts')
    <script>
        function updateState(channelId) {
            var checkbox = document.getElementById("flexSwitchCheckChecked" + channelId);
            var isChecked = checkbox.checked;
            const data = {
                id: channelId,
                value: isChecked ? 1 : 0
            };
            fetch('/api/outputchannel/set', {
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                })
                .then(response => response.json())
                .then(data => {
                    console.log('State updated successfully:', data);
                })
                .catch(error => {
                    console.error('Error updating state:', error);
                });
        }
    </script>
@endsection
@yield('scripts')
