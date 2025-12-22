<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APG Payment Debug - Chatvoo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
        }

        .header {
            background: #252526;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #007acc;
        }

        h1 {
            color: #4ec9b0;
            margin-bottom: 10px;
        }

        .controls {
            margin-bottom: 20px;
        }

        .btn {
            background: #007acc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
            font-size: 14px;
        }

        .btn:hover {
            background: #005a9e;
        }

        .btn-danger {
            background: #f44747;
        }

        .btn-danger:hover {
            background: #d13030;
        }

        .log-container {
            background: #1e1e1e;
            border: 1px solid #3c3c3c;
            border-radius: 8px;
            padding: 20px;
            max-height: 70vh;
            overflow-y: auto;
            font-size: 13px;
            line-height: 1.6;
        }

        .log-entry {
            margin-bottom: 20px;
            padding: 15px;
            background: #252526;
            border-left: 3px solid #007acc;
            border-radius: 4px;
        }

        .log-entry.error {
            border-left-color: #f44747;
        }

        .log-entry.success {
            border-left-color: #4ec9b0;
        }

        .timestamp {
            color: #858585;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .action {
            color: #4ec9b0;
            font-weight: bold;
            margin-bottom: 10px;
        }

        pre {
            background: #1e1e1e;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
            color: #ce9178;
        }

        .key {
            color: #9cdcfe;
        }

        .string {
            color: #ce9178;
        }

        .number {
            color: #b5cea8;
        }

        .boolean {
            color: #569cd6;
        }

        .null {
            color: #569cd6;
        }

        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 10px;
        }

        .status.live {
            background: #4ec9b0;
            color: #000;
        }

        .status.paused {
            background: #858585;
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #858585;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🔍 APG Payment Gateway Debug Console</h1>
        <p>Real-time monitoring of payment gateway requests and responses</p>
        <span class="status live" id="status">● LIVE</span>
    </div>

    <div class="controls">
        <button class="btn" onclick="refreshLog()">🔄 Refresh</button>
        <button class="btn" onclick="toggleAutoRefresh()">
            <span id="autoRefreshText">⏸ Pause Auto-Refresh</span>
        </button>
        <button class="btn btn-danger" onclick="clearLog()">🗑 Clear Log</button>
        <button class="btn" onclick="window.open('{{ route('payment.apg.test') }}', '_blank')">🧪 Open Test
            Page</button>
    </div>

    <div class="log-container" id="logContainer">
        <div class="empty-state">
            <p>📝 Waiting for payment requests...</p>
            <p style="margin-top: 10px; font-size: 12px;">Logs will appear here when you initiate a payment</p>
        </div>
    </div>

    <script>
        let autoRefresh = true;
        let refreshInterval;

        function formatJSON(obj) {
            return JSON.stringify(obj, null, 2)
                .replace(/"([^"]+)":/g, '<span class="key">"$1"</span>:')
                .replace(/: "([^"]*)"/g, ': <span class="string">"$1"</span>')
                .replace(/: (\d+)/g, ': <span class="number">$1</span>')
                .replace(/: (true|false)/g, ': <span class="boolean">$1</span>')
                .replace(/: null/g, ': <span class="null">null</span>');
        }

        async function refreshLog() {
            try {
                const response = await fetch('{{ route('payment.apg.debug.log') }}');
                const data = await response.json();

                const container = document.getElementById('logContainer');

                if (!data.logs || data.logs.length === 0) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <p>📝 No logs yet</p>
                            <p style="margin-top: 10px; font-size: 12px;">Initiate a payment to see logs here</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = data.logs.map(log => {
                    const isError = log.action === 'EXCEPTION' || log.error;
                    const isSuccess = log.action === 'SUCCESS';
                    const className = isError ? 'error' : (isSuccess ? 'success' : '');

                    return `
                        <div class="log-entry ${className}">
                            <div class="timestamp">${log.timestamp || 'Unknown time'}</div>
                            <div class="action">${log.action || 'UNKNOWN ACTION'}</div>
                            <pre>${formatJSON(log)}</pre>
                        </div>
                    `;
                }).reverse().join('');

            } catch (error) {
                console.error('Failed to refresh log:', error);
            }
        }

        function toggleAutoRefresh() {
            autoRefresh = !autoRefresh;
            const btn = document.getElementById('autoRefreshText');
            const status = document.getElementById('status');

            if (autoRefresh) {
                btn.textContent = '⏸ Pause Auto-Refresh';
                status.textContent = '● LIVE';
                status.className = 'status live';
                startAutoRefresh();
            } else {
                btn.textContent = '▶ Resume Auto-Refresh';
                status.textContent = '● PAUSED';
                status.className = 'status paused';
                stopAutoRefresh();
            }
        }

        function startAutoRefresh() {
            if (refreshInterval) clearInterval(refreshInterval);
            refreshInterval = setInterval(refreshLog, 2000); // Refresh every 2 seconds
        }

        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
                refreshInterval = null;
            }
        }

        async function clearLog() {
            if (!confirm('Are you sure you want to clear all logs?')) return;

            try {
                await fetch('{{ route('payment.apg.debug.clear') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                refreshLog();
            } catch (error) {
                console.error('Failed to clear log:', error);
            }
        }

        // Initial load
        refreshLog();
        startAutoRefresh();
    </script>
</body>

</html>