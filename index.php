<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spinning Wheel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="input-container">
        <label for="segmentCount">Number of Segments:</label>
        <input type="number" id="segmentCount" min="2" value="4" readonly>
        <button class="button" onclick="fetchParticipants()">Fetch Participants</button>
        <button class="button" onclick="updateWheel()">Update Wheel</button>
    </div>
    
    <div class="wheel-container">
        <div id="wheel" class="wheel"></div>
        <div class="pointer"></div>
        <div id="label" class="label hidden">Selected: </div>
    </div>
    
    <button class="button" onclick="spinWheel()">Spin</button>

    <script>
        let participants = [];

        async function fetchParticipants() {
            try {
                const response = await fetch('fetch_participants.php');
                if (!response.ok) {
                    throw new Error('Failed to fetch participants');
                }

                participants = await response.json();
                const participantCount = participants.length;
                document.getElementById('segmentCount').value = participantCount;

                updateWheel();
            } catch (error) {
                console.error('Error fetching participants:', error);
            }
        }

        function updateWheel() {
            const wheel = document.getElementById('wheel');
            const anglePerSegment = 360 / participants.length;
            const gradientStops = [];
            
            const baseColors = ['#ff6f61', '#ffcc5c', '#88d8b0', '#6a5acd'];
            for (let i = 0; i < participants.length; i++) {
                const color = baseColors[i % baseColors.length];
                const startPercent = (i * 100) / participants.length;
                const endPercent = ((i + 1) * 100) / participants.length;
                gradientStops.push(`${color} ${startPercent}% ${endPercent}%`);
            }

            wheel.style.background = `conic-gradient(${gradientStops.join(', ')})`;

            const segmentLabels = participants.map((name, index) => `Segment ${index + 1}: ${name}`);
            const labels = document.querySelectorAll('.wheel .label');
            labels.forEach((label, index) => {
                label.textContent = segmentLabels[index] || '';
            });
        }

        function spinWheel() {
            if (participants.length === 0) {
                alert('No participants to spin!');
                return;
            }

            const wheel = document.getElementById('wheel');
            const label = document.getElementById('label');
            const anglePerSegment = 360 / participants.length;
            const randomRotation = Math.floor(Math.random() * 360);
            
            label.classList.add('hidden');
            wheel.style.transform = `rotate(${randomRotation + 3600}deg)`;
            
            setTimeout(() => {
                const selectedIndex = Math.floor((360 - (randomRotation % 360)) / anglePerSegment) % participants.length;
                label.textContent = `Selected: ${participants[selectedIndex]}`;
                label.classList.remove('hidden');
            }, 4000);
        }

        updateWheel();
    </script>
</body>
</html>
