export function random(min, max) {
	return Math.floor(Math.random() * (max - min + 1) + min);
}

/**
 * Randomize array element order in-place.
 * Using Durstenfeld shuffle algorithm.
 */
export const shuffleArray = (array) => {
	for (let i = array.length - 1; i > 0; i--) {
		let j = Math.floor(Math.random() * (i + 1));
		let temp = array[i];
		array[i] = array[j];
		array[j] = temp;
	}
	return array;
};

export const findInArray = (array, value, key = 'id') => {
	for (let i = 0; i < array.length; i++) {
		if (array[i][key] === value) {
			return i;
		}
	}
	
	return null;
};

export const cloneObject = (obj) => {
	let clone;

	if (Object.prototype.toString.call(obj) === '[object Array]') {
		clone = [];
	}
	else {
		clone = {};
	}

	for (let i in obj) {
		if (!obj.hasOwnProperty(i)) {
			continue;
		}

		if (obj[i] !== null && typeof obj[i] === 'object') {
			clone[i] = cloneObject(obj[i]);
		}
		else {
			clone[i] = obj[i];
		}
	}

	return clone;
};
