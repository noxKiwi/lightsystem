"use strict";

const animationData = {
    /**
     * I am a collection of attributes that can be animated through the attr() function.
     * @type string[]
     */
    attribute: ["width", "height", "x", "y", "fill", "fill-opacity", "stroke", "stroke-opacity", "rx", "ry", "cx", "cy", "title", "visibility", "class"],
    lastcolor: 0,

    /**
     * I am an object that contains animations that cannot be animated through the attr() function.
     * Instead of this I contain a key value storage.
     * KEY is the animation name
     * VALUE is a function (pointer, value, tag)
     * @type {{text: animationRoute.text, blink: animationRoute.blink}}
     */
    route: {
        blink: function (pointer, value, tag) {
            runtime.visualization.blink.objects[tag] = {
                DOMelement: pointer,
                colors: JSON.parse(value.value).colors
            };
            return true;
        },
        noblink: function (pointer, value, tag) {
            runtime.visualization.blink.objects[tag] = null;
            Animate.animate(pointer, "fill", value);
        },
        rotate: function (pointer, value, tag) {
            let s = Snap("[finaltag=\"" + tag + "\"]");
            s.transform("r" + parseFloat(value["value"]));
        },
        image: function (pointer, value, tag) {
            let element = $("[finaltag=\"" + tag + "\"]");
            if (element.prop("tagName") !== "image") {
                Log.Info(tag + " is not an image element.");
                return;
            }
            element.attr("xlink:href", "/" + value["value"] + ".svg");
        },
        text: function (pointer, value, tag) {
            if ($(pointer).prop("tagName") !== "text") {
                Log.Info(tag + " is not a text element.");
                return;
            }
            $(pointer).html(value.display);
        }
    }
};

/**
 * I am the animation class.
 * Give me a tag and an animation, I'll handle the rest.
 */
class Animate {
    /**
     * I will iterate blinkObjects and use each element's next color.
     */
    static blink() {
        FrontendManager.getInstance().clearBlink();
        if (animationData.lastcolor === 0) {
            animationData.lastcolor = 1;
        } else {
            animationData.lastcolor = 0;
        }
        for (let tag in runtime.visualization.blink.objects) {
            if (!runtime.visualization.blink.objects.hasOwnProperty(tag)) {
                continue;
            }

            let myBlinkObject = runtime.visualization.blink.objects[tag];

            if (myBlinkObject === null) {
                continue;
            }

            Animate.animate(myBlinkObject.DOMelement, "fill", myBlinkObject.colors[animationData.lastcolor], tag);
        }
        FrontendManager.getInstance().resetBlink();
    }

    /**
     * I will add the given $animationCallback into the animation router.
     * @param keyword
     * @param animationCallback
     */
    static register(keyword, animationCallback) {
        animationData.route[keyword] = animationCallback;
    }

    /**
     * I will perform the given animation on the given tag element.
     * @param string tag
     * @param object[] animations
     * @returns {boolean}
     */
    static tag(tag, attribute, value) {
        if (typeof (tag) !== "string") {
            Log.Error("The given $tag is not a string.");
            return false;
        }

        if (tag === "") {
            Log.Error("The given $tag is an empty string.");
            return false;
        }
        if (typeof (runtime.visualization.tagList[tag]) !== "object") {
            return false;
        }

        let animationObject = runtime.visualization.tagList[tag];

        if (typeof (attribute) !== "string") {
            Log.Error("The animation attribute is not a string.");
            return false;
        }

        if (attribute === "") {
            Log.Error("The animation attribute is an empty string.");
            return false;
        }

        if (typeof (value) === "undefined") {
            Log.Error("The animation value is of type [undefined].");
            return false;
        }

        this.animate(animationObject, attribute, value, tag);
        return true;
    }

    /**
     * I will animate the given pointer's element.
     * @param pointer
     * @param attribute
     * @param value
     * @param tag
     */
    static animate(pointer, attribute, value, tag) {
        let animation;
        if (typeof value === "string") {
            value = {
                display : value,
                value   : value
            };
        }

        animation = $(pointer).parents("svg").first().data("function");
        if (typeof (animation) === "string" && animation.length > 0) {
            this.animateRoute(pointer, value, tag, animation);
        }

        if (this.animateAttribute(pointer, value, tag, attribute)) {
            return true;
        }
        if (this.animateRoute(pointer, value, tag, attribute)) {
            return true;
        }
        Log.Error("animation type " + attribute + " not found");
        return false;
    }

    /**
     * I will look for an entry in animationData.attribute for the given attribute and animate it.
     * @param pointer
     * @param value
     * @param tag
     * @param attribute
     * @returns {boolean}
     */
    static animateAttribute(pointer, value, tag, attribute) {
        if (animationData.attribute.indexOf(attribute) === -1) {
            return false;
        }
        $(pointer).attr(attribute, value.value);
        return true;
    }

    /**
     * I will look for an entry in animationData.route for the given key and run the callback.
     * @param pointer
     * @param value
     * @param tag
     * @param key
     * @returns {boolean}
     */
    static animateRoute(pointer, value, tag, key) {
        if (typeof animationData.route[key] !== "function") {
            return false;
        }
        try {
            animationData.route[key](pointer, value, tag);
        } catch (e) {
            return false;
        }
        return true;
    }
}
