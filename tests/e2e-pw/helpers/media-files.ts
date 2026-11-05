export interface MediaFile {
    name: string;
    mimeType: string;
    buffer: Buffer;
}

const ONE_PIXEL_PNG =
    "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==";

const MP4_FILE_TYPE_BOX = Buffer.concat([
    Buffer.from([0, 0, 0, 24]),
    Buffer.from("ftypmp42"),
    Buffer.from([0, 0, 0, 0]),
    Buffer.from("mp42isom"),
]);

function textFile(name: string, mimeType: string, content: string): MediaFile {
    return { name, mimeType, buffer: Buffer.from(content) };
}

export function validImage(name: string = "valid-image.png"): MediaFile {
    return {
        name,
        mimeType: "image/png",
        buffer: Buffer.from(ONE_PIXEL_PNG, "base64"),
    };
}

export function largeImage(
    kilobytes: number,
    name: string = "large-image.png",
): MediaFile {
    const image = Buffer.from(ONE_PIXEL_PNG, "base64");

    return {
        name,
        mimeType: "image/png",
        buffer: Buffer.concat([
            image,
            Buffer.alloc(kilobytes * 1024 - image.length),
        ]),
    };
}

export function notAnImage(): MediaFile {
    return textFile(
        "not-an-image.png",
        "image/png",
        "Plain text behind an image name.",
    );
}

export function plainTextFile(): MediaFile {
    return textFile(
        "notes.txt",
        "text/plain",
        "Plain text that says it is plain text.",
    );
}

export function validVideo(
    name: string = "valid-video.mp4",
    kilobytes: number = 1,
): MediaFile {
    const payload = Buffer.alloc(kilobytes * 1024, 0xab);
    const mediaDataHeader = Buffer.alloc(8);

    mediaDataHeader.writeUInt32BE(payload.length + mediaDataHeader.length, 0);
    mediaDataHeader.write("mdat", 4, "ascii");

    return {
        name,
        mimeType: "video/mp4",
        buffer: Buffer.concat([MP4_FILE_TYPE_BOX, mediaDataHeader, payload]),
    };
}

export function notAVideo(): MediaFile {
    return textFile(
        "not-a-video.mp4",
        "video/mp4",
        "Plain text behind a video name.",
    );
}

export function validSvg(): MediaFile {
    return textFile(
        "valid-image.svg",
        "image/svg+xml",
        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="16"><rect width="24" height="16" fill="#c00"/></svg>',
    );
}

export function scriptedSvg(): MediaFile {
    return textFile(
        "scripted-image.svg",
        "image/svg+xml",
        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" onload="alert(1)"><script>alert(2)</script><rect width="24" height="16"/></svg>',
    );
}

export function brokenSvg(): MediaFile {
    return textFile(
        "broken-image.svg",
        "image/svg+xml",
        '<svg xmlns="http://www.w3.org/2000/svg"><rect></svg>',
    );
}

export function htmlNamedAsSvg(): MediaFile {
    return textFile(
        "html-page.svg",
        "image/svg+xml",
        "<html><body><script>alert(1)</script></body></html>",
    );
}
