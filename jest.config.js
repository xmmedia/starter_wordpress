module.exports = {
    moduleFileExtensions: [
        'js',
        'json',
        'vue',
    ],
    transform: {
        '^.+\\.vue$': 'vue-jest',
        '.+\\.(css|styl|less|sass|scss|svg|png|jpg|ttf|woff|woff2)$': 'jest-transform-stub',
        '^.+\\.jsx?$': 'babel-jest',
    },
    moduleNameMapper: {
        '^@/(.*)$': '<rootDir>/js/src/$1',
    },
    snapshotSerializers: [
        'jest-serializer-vue',
    ],
    testMatch: [
        '<rootDir>/js/tests/**/*.spec.(js|jsx|ts|tsx)|**/__tests__/*.(js|jsx|ts|tsx)',
    ],
    // testURL: 'http://localhost/'
};
