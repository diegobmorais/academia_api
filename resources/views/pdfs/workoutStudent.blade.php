<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Treinos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        .workout-list {
            list-style-type: none;
            padding: 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .day {
            border-bottom: 1px solid #ddd;
            padding: 15px;
        }

        .day:last-child {
            border-bottom: none;
        }

        .exercise-list {
            list-style-type: none;
            padding: 0;
            margin-top: 10px;
        }

        .exercise {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 6px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        p {
            color: #888;
        }
    </style>
</head>

<body>

    <h2>Treinos - SysTrain</h2>

    <ul class="workout-list">
        @foreach ($Treinos['workout'] as $day => $workouts)
            <li class="day">
                <strong>{{ ucfirst($day) }}:</strong>
                @if (count($workouts) > 0)
                    <ul class="exercise-list">
                        @foreach ($workouts as $workout)
                            <li class="exercise">
                                Exercício - {{ $workout->exercise['description'] }}<br>
                                Repetições: {{ $workout['repetitions'] }},<br>
                                Peso: {{ $workout['weight'] }},<br>
                                Tempo: {{ $workout['time'] }} minutos
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>Sem treinos neste dia.</p>
                @endif
            </li>
        @endforeach
    </ul>

</body>

</html>
