/**
 * @type {import('semantic-release').GlobalConfig}
 */
export default {
    branches: ["release"],
    plugins: [
        [
            "@semantic-release/commit-analyzer",
            {
                preset: "conventionalcommits",
                releaseRules: [
                    { type: "refactor", release: "patch" },
                    { type: "perf", release: "patch" },
                    { type: "revert", release: "patch" },
                    { type: "docs", scope: "api", release: "patch" }
                ]
            }
        ],
        [
            "@semantic-release/release-notes-generator",
            {
                preset: "conventionalcommits"
            }
        ],
        [
            "@semantic-release/changelog",
            {
                changelogFile: "CHANGELOG.md"
            }
        ],
        [
            "@semantic-release/git",
            {
                assets: ["CHANGELOG.md"],
                message: "chore(release): v${nextRelease.version} [skip ci]\n\n${nextRelease.notes}"
            }
        ],
        [
            "@semantic-release/github",
            {
            }
        ]
    ]
}