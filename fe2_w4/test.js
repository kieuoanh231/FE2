function executePostfix(str) {
    let stack = [], operand1, operand2, tempOperand;
    let operators = ['+', '-', '*', '/'];

    for (let char of str.split(' ')) {
        // char = str.charAt(i);
        if (operators.indexOf(char) >= 0) {
            // operate
            operand2 = stack.pop();
            operand1 = stack.pop();

            tempOperand = eval(operand1 + char + operand2);
            stack.push(tempOperand);

        } else {
            stack.push(char);
        }
    }
    return stack.pop();
}

function infixToPostfix(input) {
    let stack = [], answer = [], char;
    const operands = {
        '+': 1,
        '-': 1,
        "*": 2,
        "/": 2,
    };

    for (let char of input.split(' ')) {
        let currentPrecedence = operands[char];

        if (currentPrecedence) {

            let peek = operands[stack[stack.length - 1]];

            // pop until the peek is smaller
            while (peek >= currentPrecedence) {
                answer.push(stack.pop());
                peek = operands[stack[stack.length - 1]];
            }

            stack.push(char);

        } else { // not operand, push to answer
            answer.push(char);
        }
    }

    while (stack.length > 0) {
        answer.push(stack.pop())
    }

    return answer.join(' ');
}

let expression = infixToPostfix('20 + 3 * 3 + 7');
let answer = executePostfix(expression);

console.log(expression, '\n', answer);