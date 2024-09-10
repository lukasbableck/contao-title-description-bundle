const bindField = (field, min, max, short, fontSize) => {
	let meter = addMeterToWidget(field, min, max);
	field.addEventListener("keyup", () => {
		let text = field.value;
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
	});
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

const bindTitleField = (field) => {
	bindField(field, 0, 600, 170, 20);
}

const bindDescriptionField = (field) => {
	bindField(field, 0, 990, 300, 14);
}

let pageTitle = document.querySelector("input[name=pageTitle]");
let description = document.querySelector("textarea[name=description]");
if (pageTitle && description) {
	bindTitleField(pageTitle);
	bindDescriptionField(description);
}