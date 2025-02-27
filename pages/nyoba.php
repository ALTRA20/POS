<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Paste & Draw</title>
        <style>
            body { text-align: center; }
            canvas { border: 1px solid black; cursor: crosshair; display: block; margin: auto; }
        </style>
    </head>
    <body>
        <h2>Paste Gambar & Coret-Coret</h2>
        <p>Paste gambar langsung di halaman ini (Ctrl+V)</p>
        <canvas id="drawingCanvas"></canvas>
        <br>
        <button id="saveBtn">Save as Image</button>

        <script>
            const canvas = document.getElementById('drawingCanvas');
            const ctx = canvas.getContext('2d');
            let drawing = false;

            function resizeCanvas(width, height) {
                canvas.width = width;
                canvas.height = height;
            }

            document.addEventListener('paste', (event) => {
                const items = (event.clipboardData || event.originalEvent.clipboardData).items;
                for (const item of items) {
                    if (item.type.indexOf('image') !== -1) {
                        const blob = item.getAsFile();
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const img = new Image();
                            img.onload = () => {
                                resizeCanvas(img.width, img.height);
                                ctx.drawImage(img, 0, 0);
                            };
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(blob);
                    }
                }
            });

            canvas.addEventListener('mousedown', (event) => {
                drawing = true;
                ctx.beginPath();
                ctx.moveTo(event.offsetX, event.offsetY);
            });

            canvas.addEventListener('mousemove', (event) => {
                if (!drawing) return;
                ctx.lineTo(event.offsetX, event.offsetY);
                ctx.stroke();
            });

            canvas.addEventListener('mouseup', () => {
                drawing = false;
            });

            canvas.addEventListener('mouseleave', () => {
                drawing = false;
            });

            document.getElementById('saveBtn').addEventListener('click', () => {
                const link = document.createElement('a');
                link.download = 'canvas-drawing.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });

        </script>

    </body>
</html>