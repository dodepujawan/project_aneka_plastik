const puppeteer = require("puppeteer");
const fs = require("fs");

(async () => {
    const args = process.argv.slice(2);
    if (args.length < 2) {
        console.error("Usage: node generate_pdf.js <input_html_path> <output_pdf_path>");
        process.exit(1);
    }

    const [inputHtmlPath, outputPdfPath] = args;

    if (!fs.existsSync(inputHtmlPath)) {
        console.error("Error: Input HTML file not found:", inputHtmlPath);
        process.exit(1);
    }

    try {
        console.log("Launching Puppeteer...");
        const browser = await puppeteer.launch({
            headless: true,
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-gpu',
                '--single-process',
                '--headless=new' // Mode lebih ringan
            ],
            executablePath: "/usr/bin/google-chrome" // Pastikan Chrome ada di lokasi ini
        });

        const page = await browser.newPage();
        await page.setViewport({ width: 1280, height: 1024 }); // Hindari rendering berlebihan

        console.log("Reading HTML content...");
        const content = fs.readFileSync(inputHtmlPath, "utf8");

        console.log("Setting HTML content to page...");
        await page.setContent(content, { waitUntil: "domcontentloaded", timeout: 120000 }); // Naikkan timeout

        console.log("Generating PDF...");
        await page.pdf({
            path: outputPdfPath,
            format: "A4",
            printBackground: true,
            timeout: 300000  // Timeout lebih lama
        });

        console.log("PDF successfully generated at:", outputPdfPath);
        await browser.close();
    } catch (error) {
        console.error("Error generating PDF:", error);
        process.exit(1);
    }
})();
