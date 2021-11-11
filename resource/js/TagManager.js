"use strict";

/**
 * I will handle the tag and variable system on the client-side.
 * @author Jan Nox <jan@nox.kiwi>
 * @requires FrontendManager
 */
class TagManager
{
    constructor() {
        this.tagList = [];
    }

    /**
     * I will solely return the list of all elements that have a tag attribute.
     * @returns {jQuery|HTMLElement}
     */
    static getTaggedElements(parent) {
        if (typeof (parent) !== "object") {
            return $("[tag]");
        }
        return $(parent).find("[tag]");
    }

    static hasChildTags(pointer) {
        let children     = TagManager.getTaggedElements(pointer),
            hasChildTags = false;
        $(children).each(function () {
            let childTag = $(this).attr("tag");
            if (childTag !== "") {
                hasChildTags = true;
            }
        });
        return hasChildTags;
    }

    /**
     * I will search the DOM for valid tags.
     */
    static findTags() {
        runtime.visualization.tagList = [];

        let tags     = TagManager.getTaggedElements(),
            tagCount = tags.length - 1;

        $(tags).each(function () {
            let tag = $(this).attr("tag");

            if (typeof (tag) !== "string") {
                if (! tagCount--) {
                    TagManager.findTagsDone();
                }
                return;
            }

            if (tag.length === 0) {
                if (! tagCount--) {
                    TagManager.findTagsDone();
                }
                return;
            }

            let hasChildTags = TagManager.hasChildTags(this);

            if (hasChildTags === true) {
                if (! tagCount--) {
                    TagManager.findTagsDone();
                }
                return;
            }

            let parents = $(this).parents("[tag]");

            let parentsCount = parents.length - 1;

            for (let parentIndex = 0; parentIndex <= parentsCount; parentIndex++) {
                let myTag = $(parents[parentIndex]).attr("tag");

                if (typeof (myTag) !== "string") {
                    Log.Error("myTag is not a string!");
                    continue;
                }

                if (myTag.length === 0) {
                    continue;
                }

                tag = myTag + "." + tag;
            }
            if (typeof (runtime.visualization.tagList[tag]) === "undefined") {
                runtime.visualization.tagList[tag] = [];
            }

            TagManager.setFinalTag(this, tag);
            runtime.visualization.tagList[tag].push(this);
            if (! tagCount--) {
                TagManager.findTagsDone();
            }
        });
    }

    static setFinalTag(pointer, tag) {
        $(pointer).attr("finaltag", tag);
    }

    static normalizeTag(tag) {
        return tag.
            replace(".C_DIMENSION", "").
            replace(".SCALE", "").
            replace(".F_VALUE", "").
            replace(".STATUS", "").
            replace(".ALARM", "").
            replace(".INFO", "").
            replace(".NAME", "").
            replace(".MW", "").
            replace(".SM", "");
    }

    /**
     * I will send all currently available Tags to the server.
     * This is necessary to fetch value-changes from the server to the client.
     */
    static bindTags() {
        Core.ajaxRequest({
            url  : "/?context=item&view=bind",
            data : { tags : this.getTagList() }
        }).then(function () {
            Log.Info("Tags have been bound.");
        });
    }

    static getTagList() {
        let tagList = [];
        for (let tag in runtime.visualization.tagList) {
            if (! runtime.visualization.tagList.hasOwnProperty(tag)) {
                continue;
            }
            tagList.push(tag);
        }
        return tagList;
    }

    static findTagsDone() {
        TagManager.bindTags();
    }

    static updateTags(pointer, tagprefix) {
        $(pointer).find("[tag]").first().attr("tag", tagprefix);
        TagManager.devisualizeTags(pointer);
        TagManager.findTags();
    }

    static devisualizeTags(pointer) {
    }
}
