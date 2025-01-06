<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Bracketing UI</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            font-family: 'Roboto', sans-serif;
            padding: 20px;
        }

        .bracket-container {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 10px;
        }

        .round {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .round h3 {
            font-size: 18px;
            text-transform: uppercase;
            color: #0d6efd;
            margin-bottom: 15px;
        }

        .match {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            min-width: 250px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .match:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .player {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            padding: 8px;
            border-radius: 5px;
        }

        .winner {
            color: #198754; /* Bootstrap success green */
            font-weight: bold;
        }

        .winner .badge {
            background-color: #198754;
        }

        .loser {
            color: #dc3545; /* Bootstrap danger red */
            text-decoration: line-through;
        }

        .loser .badge {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1 class="text-center my-4 text-uppercase" style="color: #0d6efd;">Match Bracket</h1>
        <div class="bracket-container">
            <div class="round">
                <h3>Round 1</h3>
                <div class="match">
                    <div class="player winner">
                        <span>Player 1</span>
                        <span class="badge text-white">Winner</span>
                    </div>
                    <div class="player loser">
                        <span>Player 2</span>
                        <span class="badge text-white">Loser</span>
                    </div>
                </div>
                <div class="match">
                    <div class="player winner">
                        <span>Player 3</span>
                        <span class="badge text-white">Winner</span>
                    </div>
                    <div class="player loser">
                        <span>Player 4</span>
                        <span class="badge text-white">Loser</span>
                    </div>
                </div>
            </div>
            <div class="round">
                <h3>Semifinals</h3>
                <div class="match">
                    <div class="player winner">
                        <span>Winner 1</span>
                        <span class="badge text-white">Winner</span>
                    </div>
                    <div class="player loser">
                        <span>Winner 2</span>
                        <span class="badge text-white">Loser</span>
                    </div>
                </div>
            </div>
            <div class="round">
                <h3>Finals</h3>
                <div class="match">
                    <div class="player winner">
                        <span>Winner SF1</span>
                        <span class="badge text-white">Winner</span>
                    </div>
                    <div class="player loser">
                        <span>Winner SF2</span>
                        <span class="badge text-white">Loser</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
