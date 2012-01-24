public class B4 {

	public static void main(String[] args) {
		// set a and b to 6 and 4
		int a = 6;
		System.out.println("a is :" + a);
		int b = 4;
		System.out.println("b is :" + b);
		// print out our method
		System.out.println("The difference is :" + difference(a, b));

	}

	// method to return the difference between a and b
	public static int difference(int a, int b) {
		int output = 0;
		if (a > b) {
			output = a - b;
			System.out.println("output is :" + output);
			return output;
		} else {
			output = b - a;
			System.out.println("output is :" + output);
			return output;
		}
	}

}
