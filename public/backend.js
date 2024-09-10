const updateWidth = (text, meter, fontSize, max, short) => {
	let textWidth = calculateTextWidth(text, fontSize);
	meter.value = textWidth;
	if (textWidth > max) {
		meter.classList.add("red");
		meter.classList.remove("yellow");
	} else {
		meter.classList.remove("red");
		if (textWidth < short) {
			meter.classList.add("yellow");
		} else {
			meter.classList.remove("yellow");
		}
	}
}

const calculateTextWidth = (text, fontSize) => {
	let canvas = document.createElement("canvas");
	let context = canvas.getContext("2d");
	context.font = fontSize + "px Arial";
	return Math.round(context.measureText(text).width);
}

const addMeterToWidget = (field, min, max) => {
	let meter = document.createElement("meter");
	meter.classList.add("text-length");
	meter.min = min;
	meter.max = max;
	field.after(meter);
	return meter;
}

const getTitle = (field) => {
	let text = field.value;
	let serpPreview = document.querySelector(".serp-preview");
	if (text == "") {
		if (serpPreview) {
			let title = serpPreview.querySelector(".title");
			if (title) {
				text = title.textContent;
				field.placeholder = text;
			}
		}
	} else {
		field.placeholder = "";
		if (serpPreview) {
			let titleTag = getTitleTag(serpPreview);
			text = titleTag.replace('%s', text);
		}
	}
	return text;
}

const extractJSON = (str) => {
	var firstOpen, firstClose, candidate;
	firstOpen = str.indexOf('{', firstOpen + 1);
	do {
		firstClose = str.lastIndexOf('}');
		if (firstClose <= firstOpen) {
			return null;
		}
		do {
			candidate = str.substring(firstOpen, firstClose + 1);
			candidate = candidate.replace(/(\w+:)|(\w+ :)/g, function (matchedStr) {
				return '"' + matchedStr.substring(0, matchedStr.length - 1) + '":';
			});
			candidate = candidate.replace(/'/g, '"');
			try {
				var res = JSON.parse(candidate);
				return [res, firstOpen, firstClose + 1];
			} catch (e) { }
			firstClose = str.substr(0, firstClose).lastIndexOf('}');
		} while (firstClose > firstOpen);
		firstOpen = str.indexOf('{', firstOpen + 1);
	} while (firstOpen != -1);
}

const getTitleTag = (serpPreview) => {
	let script = serpPreview.nextElementSibling.innerHTML;
	let json = extractJSON(script);
	if(json[0]){
		return json[0].titleTag;
	}
}

const bindTitleField = (field) => {
	let min = 0;
	let max = 600;
	let short = 170;
	let fontSize = 20;

	let meter = addMeterToWidget(field, min, max);
	updateWidth(getTitle(field), meter, fontSize, max, short);
	field.addEventListener("keyup", () => {
		let text = getTitle(field);
		updateWidth(text, meter, fontSize, max, short);
	});
}

const bindDescriptionField = (field) => {
	let min = 0;
	let max = 990;
	let short = 300;
	let fontSize = 14;

	let meter = addMeterToWidget(field, min, max);
	updateWidth(field.value, meter, fontSize, max, short);
	field.addEventListener("keyup", () => {
		let text = field.value;
		updateWidth(text, meter, fontSize, max, short);
	});
}

const init = () => {
	let pageTitle = document.querySelector("input[name=pageTitle]");
	let description = document.querySelector("textarea[name=description]");
	if (pageTitle && description) {
		if (pageTitle.nextElementSibling && pageTitle.nextElementSibling.classList.contains("text-length")) {
			return;
		}
		bindTitleField(pageTitle);
		bindDescriptionField(description);
	}
};

window.addEventListener("load", () => {
	init();
});

window.addEventListener("turbo:load", () => {
	init();
});