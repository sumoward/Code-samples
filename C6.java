public class C6 {

	public static void main(String[] args) {

		// loop the whole program for values of n from one million to 10 million
		for (int j = 1; j <= 10; j++) {
			// set n
			int n = 1000000 * j;

			// populate the array
			int[] testArray = new int[n];

			for (int i = 0; i < n; i++) {
				testArray[i] = 1;
				// test the array size
				// System.out.println("test" +testArray[i]);
			}

			// print out the size of the array
			// System.out.println(testArray.length);

			// System.out.println(minValueIndex(testArray, n));

			// Initialise the duration at zero
			double duration = 0;
			// loop 5 times to carry out results smoothing
			for (int i = 0; i < 5; i++) {
				long start = System.currentTimeMillis();

				// use our method to check the array
				minValueIndex(testArray, n);
				// print out the minvalue for our array
				// System.out.print("the lowest value is at location : ");
				// System.out.println(minValueIndex(testArray, n));
				long end = System.currentTimeMillis();
				duration += end - start;
			}
			duration = duration / 5;
			System.out.println("Running Time for " + n + " sized array lasts "
					+ duration);
		}

	}

	// method to get the minvalue
	public static int minValueIndex(int[] A, int n) {
		int minvalue = 0;
		for (int k = 1; k < n; k++) {
			if (A[minvalue] > A[k]) {
				minvalue = k;
			}
		}
		return minvalue;
	}

}
