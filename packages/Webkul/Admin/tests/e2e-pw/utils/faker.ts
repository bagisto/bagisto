import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const usedNames = new Set();
const usedEmails = new Set();
const usedNumbers = new Set();
const usedSlugs = new Set();

export function generateName() {
    const adjectives = [
        "Cool",
        "Smart",
        "Fast",
        "Sleek",
        "Innovative",
        "Shiny",
        "Bold",
        "Elegant",
        "Epic",
        "Mystic",
        "Brilliant",
        "Luminous",
    ];

    const nouns = [
        "Star",
        "Vision",
        "Echo",
        "Spark",
        "Horizon",
        "Nova",
        "Shadow",
        "Wave",
        "Pulse",
        "Vortex",
        "Zenith",
        "Element",
    ];

    let name = "";

    do {
        const adj = adjectives[Math.floor(Math.random() * adjectives.length)];
        const noun = nouns[Math.floor(Math.random() * nouns.length)];

        name = `${adj} ${noun}`;
    } while (usedNames.has(name));

    usedNames.add(name);

    return name;
}

export function generateFirstName() {
    const firstNames = [
        "James",
        "Emma",
        "Liam",
        "Olivia",
        "Noah",
        "Ava",
        "William",
        "Sophia",
        "Benjamin",
        "Isabella",
        "Lucas",
        "Mia",
    ];

    return firstNames[Math.floor(Math.random() * firstNames.length)];
}

export function generateLastName() {
    const lastNames = [
        "Smith",
        "Johnson",
        "Brown",
        "Williams",
        "Jones",
        "Garcia",
        "Miller",
        "Davis",
        "Rodriguez",
        "Martinez",
        "Hernandez",
        "Lopez",
    ];

    return lastNames[Math.floor(Math.random() * lastNames.length)];
}

export function generateFullName() {
    return `${generateFirstName()} ${generateLastName()}`;
}

export function generateEmail() {
    const adjectives = [
        "Cool",
        "Smart",
        "Fast",
        "Sleek",
        "Innovative",
        "Shiny",
        "Bold",
        "Elegant",
        "Epic",
        "Mystic",
        "Brilliant",
        "Luminous",
    ];

    const nouns = [
        "Star",
        "Vision",
        "Echo",
        "Spark",
        "Horizon",
        "Nova",
        "Shadow",
        "Wave",
        "Pulse",
        "Vortex",
        "Zenith",
        "Element",
    ];

    let email = "";

    do {
        const adj = adjectives[Math.floor(Math.random() * adjectives.length)];
        const noun = nouns[Math.floor(Math.random() * nouns.length)];
        const number = Math.floor(1000 + Math.random() * 9000);

        email = `${adj}${noun}${number}@example.com`.toLowerCase();
    } while (usedEmails.has(email));

    usedEmails.add(email);

    return email;
}

export function generatePhoneNumber() {
    let phoneNumber;

    do {
        phoneNumber = Math.floor(6000000000 + Math.random() * 4000000000);
    } while (usedNumbers.has(phoneNumber));

    usedNumbers.add(phoneNumber);

    return `${phoneNumber}`;
}

export function generateSKU() {
    const letters = Array.from({ length: 3 }, () =>
        String.fromCharCode(65 + Math.floor(Math.random() * 26))
    ).join("");

    const numbers = Math.floor(1000 + Math.random() * 9000);

    return `${letters}${numbers}`;
}

export function generateSlug(delimiter = "-") {
    let slug;

    do {
        const name = generateName();

        const randomStr = Math.random().toString(36).substring(2, 8);

        slug = `${name
            .toLowerCase()
            .replace(/\s+/g, delimiter)}${delimiter}${randomStr}`;
    } while (usedSlugs.has(slug));

    usedSlugs.add(slug);

    return slug;
}

export function generateDescription(length = 255) {
    const phrases = [
        "An innovative and sleek design.",
        "Built for speed and efficiency.",
        "Experience the future today.",
        "A perfect blend of style and power.",
        "Engineered to perfection.",
        "Designed for those who dream big.",
        "Unleash creativity with this masterpiece.",
        "A game-changer in every way.",
        "Smart, fast, and reliable.",
        "The perfect companion for your journey.",
        "Crafted with precision and excellence.",
        "Innovation that redefines possibilities.",
        "Enhancing your experience like never before.",
        "Where technology meets elegance.",
        "Power, performance, and perfection combined.",
        "Redefining the way you experience the world.",
        "A masterpiece of engineering and design.",
        "Unmatched quality and exceptional performance.",
        "Designed to elevate your lifestyle.",
        "Beyond expectations, beyond limits.",
    ];

    let description = "";

    while (description.length < length) {
        let phrase = phrases[Math.floor(Math.random() * phrases.length)];

        if (description.length + phrase.length <= length) {
            description += (description ? " " : "") + phrase;
        } else {
            description +=
                " " + phrase.substring(0, length - description.length);
            break;
        }
    }

    return description.trim();
}

export function generateHostname() {
    const words = [
        "tech",
        "cloud",
        "byte",
        "stream",
        "nexus",
        "core",
        "pulse",
        "data",
        "sync",
        "wave",
        "hub",
        "zone",
    ];

    const domains = [".com", ".net", ".io", ".ai", ".xyz", ".co"];

    const part1 = words[Math.floor(Math.random() * words.length)];
    const part2 = words[Math.floor(Math.random() * words.length)];
    const domain = domains[Math.floor(Math.random() * domains.length)];

    return `https://${part1}${part2}${domain}`;
}

export function randomElement(array) {
    return array[Math.floor(Math.random() * array.length)];
}

export function getImageFile(
    directory = path.resolve(__dirname, "../data/images/")
) {
    if (!fs.existsSync(directory)) {
        throw new Error(`Directory does not exist: ${directory}`);
    }

    const files = fs.readdirSync(directory);
    const imageFiles = files.filter((file) =>
        /\.(gif|jpeg|jpg|png|svg|webp)$/i.test(file)
    );

    if (!imageFiles.length) {
        throw new Error("No image files found in the directory.");
    }

    const randomIndex = Math.floor(Math.random() * imageFiles.length);

    return path.join(directory, imageFiles[randomIndex]);
}
