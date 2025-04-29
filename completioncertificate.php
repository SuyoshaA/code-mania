<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <!-- Certificate -->
    <div id="certificate" class="relative bg-white shadow-xl p-20 rounded-lg max-w-4xl w-full text-center border border-gray-300">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-center bg-no-repeat bg-contain opacity-10 z-0" style="background-image: url('logo.png');"></div>
        
        <!-- Certificate Content -->
        <div class="relative z-10">
            <!-- Logo -->
            <img src="logo.png" alt="Logo" class="h-24 mx-auto mb-8">
            <h1 class="text-5xl font-bold text-gray-800">Certificate of Completion</h1>
            <p class="text-2xl text-gray-600 mt-6">This certifies that</p>
            <div class="mt-8">
                <span class="block text-4xl font-semibold text-gray-700 underline">[Recipient Name]</span>
            </div>
            <p class="text-2xl text-gray-600 mt-8">has successfully completed the course</p>
            <div class="mt-8">
                <span class="block text-3xl font-medium text-gray-700 italic">[Course Name]</span>
            </div>
            <p class="text-lg text-gray-500 mt-10">Awarded on</p>
            <p id="currentDate" class="text-2xl font-medium text-gray-700"></p>
            <div class="flex justify-between mt-16 px-20">
                <div class="text-left">
                    <p class="text-gray-700 font-semibold">[Instructor Name]</p>
                    <p class="text-gray-500 text-md">Instructor</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-700 font-semibold">THE EXPLORER</p>
                    <p class="text-gray-500 text-md">Organization</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Download Button -->
    <div class="mt-8 flex justify-center">
        <button id="download" class="bg-blue-500 text-white py-4 px-8 rounded-lg text-xl hover:bg-blue-600">
            Download Certificate
        </button>
    </div>

    <script>
        // Automatically set the current date
        const currentDate = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = currentDate.toLocaleDateString('en-US', options);

        // Download certificate as PDF
        document.getElementById('download').addEventListener('click', () => {
            const element = document.getElementById('certificate');
            
            // Generate PDF using html2pdf.js
            html2pdf().set({
                margin: 0, // No margin
                filename: 'certificate.pdf',
                html2canvas: {
                    scale: 3, // High-quality PDF
                },
                jsPDF: {
                    unit: 'px',
                    format: [element.offsetWidth, element.offsetHeight], // Use exact dimensions
                    orientation: 'portrait',
                },
            }).from(element).save();
        });
    </script>
</body>
</html>
