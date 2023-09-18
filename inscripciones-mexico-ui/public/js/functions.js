
function getDateTimeStringFormatted(dateTimeUTC, languageCode, convertToLocaleTimeZone) {
    let dateUTC = new Date(dateTimeUTC);
    if (convertToLocaleTimeZone) {
        return '' + ((dateUTC).toLocaleString(
            languageCode, {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
                hour12: true,
                localeMatcher: "best fit",
            }));
    } else {
        return new Intl.DateTimeFormat(
            languageCode, {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
                hour12: true,
                localeMatcher: "best fit",
                timeZone: 'Etc/UTC',
            }).format(dateUTC);
    }
}

function getDateStringFormatted(dateTimeUTC, languageCode, convertToLocaleTimeZone) {
    let dateUTC = new Date(dateTimeUTC);
    if (convertToLocaleTimeZone) {
        return '' + ((dateUTC).toLocaleString(
            languageCode, {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                localeMatcher: "best fit",
            }));
    } else {
        return new Intl.DateTimeFormat(
            languageCode, {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                localeMatcher: "best fit",
                timeZone: 'Etc/UTC',
            }).format(dateUTC);
    }
}
