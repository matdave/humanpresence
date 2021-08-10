# Human Presence for MODX

This package works with https://www.humanpresence.io/ to determine if a valid human presence is on your site.

## Installation

1. Install with the Package Manager.
2. Navigate to System Settings
3. Search for `humanpresence.apikey`
4. Set the value to your api key from https://www.humanpresence.io/

## Usage

The site package will automatically add the tracking script to your site for determining human presence level.

### FormIt

You can protect your **FormIt** forms by adding the hook `HumanPresence.FormIt.Hook`. There are currently no additional
settings needed. However, you can configure the confidence level in the system settings by changing the value
of `humanpresence.confidence` to either a heigher or lower number. The maximum value returned is 100, so do not set it
lower than that.

### Output Modifier

You can block certain elements from being visible with an output modifier. Just add `:humanpresence` to any Element
call. E.g.
```[[$phoneNumber:humanpresence]]```

**WARNING** the human presence can not be determined on first load. The initial script has to load before a presence
value can be determined. Additionally, the presence script may be blocked by ad-blockers. As a result, it is suggested
that this is not used on critical forms or elements.
