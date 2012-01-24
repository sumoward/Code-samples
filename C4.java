public class C4 {

	public static void main(String[] args) {
		// set a and b to 6 and 4
		int a = 6;
		int b = 4;

		// loop our method for testing
		double duration = 0;
		// loop 5 times to carry out results smoothing
		for (int i = 0; i < 5; i++) {
			long start = System.currentTimeMillis();

			// print out our method
			System.out.println("The difference is :");
			System.out.println(difference(a, b));

			long end = System.currentTimeMillis();
			duration += end - start;
		}

		duration = duration / 5;
		System.out.println("Running Time " + duration);

	}

	// method to return the difference between a and b
	public static int difference(int a, int b) {

		int output = 0;
		if (a > b) {
			output = a - b;
			return output;
		} else {
			output = b - a;
			return output;
		}

	}

}
