module.exports = {
    root: true,
    env: {
        node: true,
        browser: true,
        "cypress/globals": true,
    },
    extends: ['plugin:vue/recommended', 'eslint:recommended'],
    rules: {
        "no-console": process.env.NODE_ENV === "production" ? "error" : "off",
        "no-debugger": process.env.NODE_ENV === "production" ? "error" : "off",
        "max-len": ["error", {
            "code": 120, // so we have a bit of grace
            "ignoreComments": true,
            "ignoreUrls": true,
            "ignoreStrings": true,
        }],
        "comma-dangle": ["error", {
            "arrays": "always-multiline",
            "objects": "always-multiline",
            "imports": "always-multiline",
            "exports": "never",
            "functions": "only-multiline",
        }],
        "vue/max-attributes-per-line": ["error", {
            "singleline": 3,
            "multiline": {
                "max": 1,
                "allowFirstLine": true,
            },
        }],
        "vue/html-indent": ["error", 4],
        "vue/html-self-closing": [
            "error", {
                "html": {
                    "void": "any",
                    "normal": "any",
                    "component": "always",
                },
                "svg": "any",
                "math": "always",
            },
        ],
        "vue/html-closing-bracket-newline": ["error", {
            "singleline": "never",
            "multiline": "never"
        }],
        "vue/multiline-html-element-content-newline": "off",
        "vue/singleline-html-element-content-newline": "off",
    },
    reportUnusedDisableDirectives: true,
    parserOptions: {
        parser: "babel-eslint",
    },
    plugins: [
        "cypress",
    ],
};
